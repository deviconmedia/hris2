@extends('layouts.admin_layout.app')

@section('title')
    Beranda
@endsection

@push('css')
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
@endpush

@section('content')
    <h6 class="text-muted" id="greeting"></h6>
    <div class="col-12 col-lg-9">
        <div class="row">
            <div class="col-6 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body px-4 py-4-5">
                        <div class="row">
                            <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                <h1> <i class="bi bi-people"></i></h1>
                            </div>
                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                <h6 class="text-muted font-semibold">Checkin Hari ini</h6>
                                <h6 class="font-extrabold mb-0" id="dataCheckin"></h6>
                                <small class="text-muted"><span id="jmlCheckin"></span>/<span id="jmlStaff"></span> sudah
                                    checkin</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body px-4 py-4-5">
                        <div class="row">
                            <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                <h1> <i class="bi bi-calendar-check"></i></h1>
                            </div>
                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                <h6 class="text-muted font-semibold">Pengajuan Cuti</h6>
                                <h6 class="font-extrabold mb-0" id="dataCuti"></h6>
                                <small class="text-muted"><span id="jmlCutis"></span>/<span id="totalCutis"></span> sudah
                                    disetujui</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header">
                        <h4><i class="fas fa-user-clock"></i> Rekam Kehadiran</h4>
                    </div>
                    <div class="card-body text-center">
                        <h1 class="clock mb-5" id="clock2"></h1>
                        @if (empty($data['absen']['jam_masuk']) && empty($data['absen']['jam_pulang']))
                            <p class="badge bg-danger">Anda belum check-in hari ini</p>
                        @else
                            <small class="text-muted">Checkin: {{ $data['absen']['jam_masuk'] }} | Checkout: {{ $data['absen']['jam_pulang'] ?? '-' }}</small>
                            <h6>Status Kehadiran:
                                <span
                                    class="badge bg-{{ $data['absen']['status'] == 'hadir' ? 'success' : ($data['absen']['status'] == 'terlambat' ? 'danger' : ($data['absen']['status'] == 'pulang cepat' ? 'warning' : 'warning')) }} text-capitalize">
                                    {{ $data['absen']['status'] }}
                                </span>
                            </h6>
                        @endif
                        <form id="rekamKehadiranForm" method="POST">
                            <div class="form-group">
                                <label for="shift_id" class="form-label">Pilih Jam Kerja</label>
                                <select name="shift_id" id="shift_id" class="form-select">
                                    @foreach ($data['shifts'] as $shift)
                                        <option value="{{ $shift->id }}">{{ $shift->nama_shift }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <input type="hidden" name="latitude" id="latitude">
                            <input type="hidden" name="longitude" id="longitude">
                            <button type="submit" class="btn btn-block btn-success rounded-pill shadow-lg my-3"
                                id="recordBtn">Rekam</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-3">
        <div class="card">
            <div class="card-body py-4 px-4">
                <div class="d-flex align-items-center">
                    <div class="avatar avatar-xl">
                        <img src="{{ asset($data['currentUser']->image_uri) }}" alt="Face 1">
                    </div>
                    <div class="ms-3 name">
                        <h5 class="font-bold">{{ $data['currentUser']->name }}</h5>
                        <small class="text-muted mb-0">{{ $data['currentUser']->karyawan->kode }}</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h6>Most Recent Attendance</h6>
            </div>
            <div class="card-content pb-4" id="userLastActivityContainer">

            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        $(document).ready(function() {
            getCountData();
            getLastActivity();
            setInterval(getCountData, 60000);
            setInterval(getLastActivity, 60000);
            setGreetingMessage();
            setInterval(updateClock, 1000);
            updateClock();
        });

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;

                // Set hidden field values
                document.getElementById('latitude').value = lat;
                document.getElementById('longitude').value = lng;

                // Update map
                map.setView([lat, lng], 15);
                L.marker([lat, lng]).addTo(map).bindPopup("Lokasi Anda Saat Ini").openPopup();
            }, function(error) {
                console.error("Geolocation error: ", error);
            });
        } else {
            console.error("Geolocation tidak didukung oleh browser Anda.");
        }

        //Set Clock
        function updateClock() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            document.getElementById('clock2').innerText = `${hours}:${minutes}:${seconds}`;
        }

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });



        function getCountData() {
            $.ajax({
                url: '/beranda/getCount',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    $('#dataCheckin').text(data.checkins);
                    $('#jmlCheckin').text(data.checkins);
                    $('#dataCuti').text(data.cutis);
                    $('#jmlStaff').text(data.staffs);
                    $('#jmlCutis').text(data.cutis);
                    $('#totalCutis').text(data.totalCutis);
                },
                error: function(xhr, status, error) {
                    console.error('AJAX error: ' + error);
                }
            });
        }


        /*
         * Get user last activity
         */
        function getLastActivity() {
            $.ajax({
                url: '/beranda/getLastCheckin',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    var container = $('#userLastActivityContainer');
                    container.empty();

                    data.activities.forEach(function(activity) {
                        var activityHtml = `
                        <div class="recent-message d-flex px-4 py-3">
                            <div class="avatar avatar-lg">
                                <img src="${activity.image_uri}" alt="Avatar">
                            </div>
                            <div class="name ms-4">
                                <h5 class="mb-1">${activity.name}</h5>
                                <small class="text-muted mb-0">${activity.time}</small>
                            </div>
                        </div>
                    `;
                        container.append(activityHtml);
                    });
                },
                error: function(xhr, status, error) {
                    console.error('AJAX error: ' + error);
                }
            });
        }

        /*
         * Set greeting message
         */
        function setGreetingMessage() {
            const now = new Date();
            const hours = now.getHours();
            let greeting;

            if (hours < 12) {
                greeting = 'Selamat Pagi';
            } else if (hours < 18) {
                greeting = 'Selamat Sore';
            } else {
                greeting = 'Selamat Malam';
            }

            const userName = '{{ Auth::user()->name }}';
            $('#greeting').text(`${greeting} ${userName}, Selamat datang di dasbor Anda!`);
        }

         //SIMPAN DATA
         $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#rekamKehadiranForm').on('submit', function(e) {
            e.preventDefault();

            var formData = {
                shift_id: $('#shift_id').val(),
                latitude: $('#latitude').val(),
                longitude: $('#longitude').val(),
            }

            $.ajax({
                url: '{{ route('presensi.store') }}',
                type: 'POST',
                data: formData,
                beforeSend: function() {
                    $('#recordBtn').prop('disabled', true).html(
                        '<i class="fas fa-spinner fa-spin"></i> Sedang Merekam...');
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message, 'Berhasil', {
                            timeOut: 3000
                        });

                        setTimeout(() => {
                            window.location.href = '{{ route('home') }}';
                        }, 1500);
                    } else {
                        toastr.error(response.message, 'Gagal', {
                            timeOut: 3000
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.log(error);
                    let errorMessage = 'Terjadi kesalahan saat mengirim data!';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }

                    toastr.error(errorMessage, 'Gagal', {
                        timeOut: 3000
                    });
                },

                complete: function() {
                    $('#recordBtn').prop('disabled', false).html('Rekam');
                }
            });
        });
    </script>
@endpush
