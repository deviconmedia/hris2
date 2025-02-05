@extends('layouts.admin_layout.app')

@section('title')
    Rekam Kehadiran
@endsection

@section('subtitle')
    Halaman Rekam Kehadiran
@endsection

@push('css')
@endpush

@section('content')
    <div class="col-12 col-md-6 col-lg-6">
        <div class="card shadow">
            <div class="card-header">
                <div class="alert d-none" id="errorMsg"></div>
                <div class="alert d-none" id="successMsg"></div>
                <div class="row">
                    <h5 class="card-title"><i class="fas fa-user-clock"></i> Rekam Kehadiran Anda |
                        {{ $data['currentDay'] }}</h5>
                </div>
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
    {{-- Lokasi Presensi --}}
    <div class="col-12 col-md-6 col-lg-6">
        <div class="card shadow">
            <div class="card-header">
                <div class="alert d-none" id="errorMsg"></div>
                <div class="alert d-none" id="successMsg"></div>
                <div class="row">
                    <h5 class="card-title"><i class="fas fa-map-marker-alt"></i> Lokasi Anda</h5>
                </div>
            </div>
            <div class="card-body">
                <div id="map" style="height: 250px;" class="my-4"></div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Informasi Shift</h5>
                    <p>Informasi yang ditampilkan sesuai dengan shift yang Anda pilih.</p>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Shift</th>
                                    <th>Jam Mulai</th>
                                    <th>Sampai</th>
                                    <th>Jam Selesai</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data['shifts'] as $item)
                                    <tr>
                                        <td>{{ $item->nama_shift }}</td>
                                        <td>{{ $item->jam_mulai }}</td>
                                        <td>{{ $item->jam_batas_mulai }}</td>
                                        <td>{{ $item->jam_selesai }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <!-- Load Leaflet CSS & JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        //Tampil Jam Berjalan (Digital)
        function updateClock() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            document.getElementById('clock2').innerText = `${hours}:${minutes}:${seconds}`;
        }
        setInterval(updateClock, 1000);
        updateClock();

        // Initialize Leaflet map
        const map = L.map('map').setView([0, 0], 13); // Default view
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Get device's current location
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
                lokasi: $('#latitude').val() + "," + $('#longitude').val(),
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
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message,
                            confirmButtonText: 'OK'
                        }).then(() => {
                            window.location.href = '{{ route('presensi.index') }}';
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: response.message,
                            confirmButtonText: 'OK'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.log(error);
                    let errorMessage = 'Terjadi kesalahan saat mengirim data!';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: errorMessage,
                        confirmButtonText: 'OK'
                    });
                },

                complete: function() {
                    $('#recordBtn').prop('disabled', false).html('Rekam');
                }
            });
        });
    </script>
@endpush
