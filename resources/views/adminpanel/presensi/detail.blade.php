@extends('layouts.admin_layout.app')

@section('title')
    Detail Presensi
@endsection

@section('subtitle')
    Detail Rekaman Presensi
@endsection

@push('css')
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
@endpush

@section('content')
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <a href="{{ route('presensi.myAttendances') }}" class="btn btn-dark"><i class="bi bi-arrow-left"></i>
                    Kembali</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <tr>
                            <th>Nama Pegawai</th>
                            <td>{{ $presensi->karyawan->nama }}</td>
                        </tr>
                        <tr>
                            <th>Kode Pegawai</th>
                            <td>{{ $presensi->karyawan->kode }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Rekam</th>
                            <td>{{ date('d-m-Y', strtotime($presensi->tgl_rekam)) }}</td>
                        </tr>
                        <tr>
                            <th>Jam Masuk</th>
                            <td>{{ $presensi->jam_masuk }}</td>
                        </tr>
                        <tr>
                            <th>Jam Pulang</th>
                            <td>{{ $presensi->jam_pulang ?? "Tidak ada data" }}</td>
                        </tr>
                        <tr>
                            <th>Shift</th>
                            <td>{{ $presensi->shift->nama_shift }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td class="text-capitalize">{{ $presensi->status }}</td>
                        </tr>
                    </table>
                    <strong>Lokasi Presensi</strong>
                    <div id="map" style="height: 300px;"></div>
                    <div class="mt-5">
                        <a href="https://www.google.com/maps?q={{ $presensi->lokasi }}" target="_blank" class="btn btn-danger">
                            <i class="bi bi-geo-alt-fill"></i> Lihat di Google Maps
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        const attendanceLocation = "{{ $presensi->lokasi }}";
        const [latitude, longitude] = attendanceLocation.split(',').map(coord => parseFloat(coord.trim()));

        if (!isNaN(latitude) && !isNaN(longitude)) {
            const map = L.map('map').setView([latitude, longitude], 15);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            L.marker([latitude, longitude]).addTo(map).bindPopup("Lokasi Kehadiran").openPopup();
        } else {
            document.getElementById('map').innerHTML = "<p>Lokasi tidak tersedia</p>";
        }
    </script>
@endpush
