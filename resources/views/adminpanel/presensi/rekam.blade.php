@extends('layouts.admin_layout.app')

@section('title')
    Rekam Kehadiran
@endsection

@section('subtitle')
    Formulir Rekam Kehadiran
@endsection

@section('content')
    <div class="col-12">
        <div class="row">
            <div class="col-12 col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title"><i class="bi bi-person-bounding-box"></i> Rekam Kehadiran | {{ $data['currentDay'] }}</h5>
                    </div>
                    <div class="card-body">
                        <h1 class="clock mb-5 text-primary text-center" id="clock2"></h1>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title"><i class="bi bi-geo-alt-fill"></i> Lokasi Anda</h5>
                    </div>
                    <div class="card-body">
                        <div id="map" style="height: 250px;" class="my-4"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

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

        //Set Location
        const map = L.map('map').setView([0, 0], 13); // Default view
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;

                document.getElementById('latitude').value = lat;
                document.getElementById('longitude').value = lng;

                map.setView([lat, lng], 15);
                L.marker([lat, lng]).addTo(map).bindPopup("Lokasi Anda Saat Ini").openPopup();
            }, function(error) {
                console.error("Geolocation error: ", error);
            });
        } else {
            console.error("Geolocation tidak didukung oleh browser Anda.");
        }

    </script>
@endpush
