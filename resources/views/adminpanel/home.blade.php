@extends('layouts.admin_layout.app')

@section('title')
    Beranda
@endsection

@section('content')
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
                                <h6 class="font-extrabold mb-0">112.000</h6>
                                <small class="text-muted">5/43 sudah checkin</small>
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
                                <h6 class="font-extrabold mb-0">183.000</h6>
                                <small class="text-muted">3/7 sudah disetujui</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- <div class="col-6 col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body px-4 py-4-5">
                        <div class="row">
                            <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                <div class="stats-icon green mb-2">
                                    <i class="iconly-boldAdd-User"></i>
                                </div>
                            </div>
                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                <h6 class="text-muted font-semibold">Following</h6>
                                <h6 class="font-extrabold mb-0">80.000</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body px-4 py-4-5">
                        <div class="row">
                            <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                <div class="stats-icon red mb-2">
                                    <i class="iconly-boldBookmark"></i>
                                </div>
                            </div>
                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                <h6 class="text-muted font-semibold">Saved Post</h6>
                                <h6 class="font-extrabold mb-0">112</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header">
                        <h4><i class="fas fa-user-clock"></i> Rekam Kehadiran</h4>
                    </div>
                    <div class="card-body">
                        <h1 class="clock text-primary text-center" id="clock2"></h1>
                        @if (empty($data['absen']['jam_masuk']) && empty($data['absen']['jam_pulang']))
                            <div class="text-center mb-5">
                                <p class="badge bg-danger">Anda belum check-in hari ini</p>
                            </div>
                        @else
                            <h6>Status Kehadiran:
                                <span
                                    class="badge bg-{{ $data['absen']['status'] == 1 ? 'success' : ($data['absen']['status'] == 2 ? 'danger' : ($data['absen']['status'] == 3 ? 'warning' : 'warning')) }}">
                                    {{ $data['absen']['status'] == 1
                                        ? 'Sudah Absen'
                                        : ($data['absen']['status'] == 2
                                            ? 'Terlambat'
                                            : ($data['absen']['status'] == 3
                                                ? 'Tidak Ada'
                                                : 'Tidak Diketahui')) }}
                                </span>
                            </h6>
                            <p>Absen Masuk: {{ $data['absen']['jam_masuk'] ?? 'Tidak Ada Data' }}</p>
                            <p>Absen Pulang: {{ $data['absen']['jam_pulang'] ?? 'Tidak Ada Data' }}</p>
                        @endif
                        <form method="post" id="rekamForm">
                            <div class="form-group">
                                <label for="shift_id" class="form-label">Pilih Shift</label>
                                <select name="shift_id" id="shift_id" class="form-select">
                                    @foreach ($data['shifts'] as $shift)
                                        <option value="{{ $shift->id }}">{{ $shift->nama_shift }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit"
                                class="btn btn-block btn-success rounded-pill shadow-lg mt-2">Rekam</button>
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
                <h4>Aktivitas Terakhir</h4>
            </div>
            <div class="card-content pb-4">
                <div class="recent-message d-flex px-4 py-3">
                    <div class="avatar avatar-lg">
                        <img src="./assets/compiled/jpg/4.jpg">
                    </div>
                    <div class="name ms-4">
                        <h5 class="mb-1">Hank Schrader</h5>
                        <h6 class="text-muted mb-0">@johnducky</h6>
                    </div>
                </div>
                <div class="recent-message d-flex px-4 py-3">
                    <div class="avatar avatar-lg">
                        <img src="./assets/compiled/jpg/5.jpg">
                    </div>
                    <div class="name ms-4">
                        <h5 class="mb-1">Dean Winchester</h5>
                        <h6 class="text-muted mb-0">@imdean</h6>
                    </div>
                </div>
                <div class="recent-message d-flex px-4 py-3">
                    <div class="avatar avatar-lg">
                        <img src="./assets/compiled/jpg/1.jpg">
                    </div>
                    <div class="name ms-4">
                        <h5 class="mb-1">John Dodol</h5>
                        <h6 class="text-muted mb-0">@dodoljohn</h6>
                    </div>
                </div>
                <div class="px-4">
                    <button class='btn btn-block btn-xl btn-outline-primary font-bold mt-3'>Start Conversation</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        //Set Clock
        function updateClock() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            document.getElementById('clock2').innerText = `${hours}:${minutes}:${seconds}`;
        }
        setInterval(updateClock, 1000);
        updateClock();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function getCountData() {
            $.ajax({
                url: '/dashboard/get-count/',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    $('#employees').text(data.employees);
                    $('#attendances').text(data.attendances);
                    $('#activeUsers').text(data.activeUsers);
                    $('#inactiveUsers').text(data.inactiveUsers);
                },
                error: function(xhr, status, error) {
                    console.error('AJAX error: ' + error);
                }
            });
        }

        $(document).ready(function() {
            getCountData();
            setInterval(getCountData, 60000);
        });

    </script>
@endpush
