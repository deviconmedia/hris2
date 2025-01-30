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

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
         function updateClock() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            document.getElementById('clock2').innerText = `${hours}:${minutes}:${seconds}`;
        }
        setInterval(updateClock, 1000);
        updateClock();
    </script>
@endpush
