@extends('layouts.admin_layout.app')

@section('title')
    Presensi Karyawan
@endsection

@section('subtitle')
    Rekaman Presensi Karyawan
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('mazer/assets/extensions/choices.js/public/assets/styles/choices.css') }}">
@endpush

@section('content')
    <div class="col-12">
        <form id="filterDataForm" method="GET" data-url="{{ route('laporan_kehadiran.getData') }}">
            <div class="row my-3">
                <div class="col-6 col-md-4 col-lg-4">
                    <div class="form-group">
                        <label for="karyawan_id" class="form-label">Pilih Karyawan</label>
                        <select name="karyawan_id" id="karyawan_id" class="form-select choices">
                            @foreach ($data['staffs'] as $staff)
                                <option value="{{ $staff->id }}">{{ $staff->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg-4">
                    <div class="form-group">
                        <label for="date_record" class="form-label">Pilih Periode</label>
                        <select name="date_record" id="date_record" class="form-select choices">
                            <option value="all">All</option>
                            <option value="today">Today</option>
                            <option value="last_7_days">Last 7 Days</option>
                            <option value="last_30_days">Last 30 Days</option>
                            <option value="this_month">This Month</option>
                            <option value="last_month">Last Month</option>
                        </select>
                    </div>
                </div>
            </div>
        </form>
        <div class="card">
            <div class="card-header">
                <div class="card-header">
                    <button class="btn btn-sm btn-secondary" onclick="refreshTable()"><i class="bi bi-arrow-clockwise"></i>
                        Refresh</button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table" id="laporanKehadiranTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama</th>
                                    <th>Tanggal</th>
                                    <th>Jam Masuk</th>
                                    <th>Jam Pulang</th>
                                    <th>Shift</th>
                                    <th>Status</th>
                                    <th>Opsi</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('mazer/assets/extensions/choices.js/public/assets/scripts/choices.js') }}"></script>
    <script src="{{ asset('mazer/assets/static/js/pages/form-element-select.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#laporanKehadiranTable').DataTable({
                processing: true,
                serverside: true,
                ajax: {
                    url: $('#filterDataForm').data('url'),
                    data: function(d) {
                        d.karyawan_id = $('#karyawan_id').val();
                        d.date_record = $('#date_record').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'nama_karyawan',
                        name: 'Nama Karyawan'
                    },
                    {
                        data: 'tgl_rekam',
                        name: 'Tanggal',
                        render: function(data, type, row) {
                            if (data) {
                                return moment(data).format('DD-MM-YYYY');
                            }
                            return 'Tidak Ada Data';
                        }
                    },
                    {
                        data: 'jam_masuk',
                        name: 'Jam Masuk',
                        render: function(data, type, row) {
                            return data ? data : '-';
                        }
                    },
                    {
                        data: 'jam_pulang',
                        name: 'Jam Pulang',
                        render: function(data, type, row) {
                            return data ? data : '-';
                        }
                    },
                    {
                        data: 'nama_shift',
                        name: 'Nama Shift',
                        render: function(data, type, row) {
                            return data ? data : '-';
                        }
                    },
                    {
                        data: 'status',
                        name: 'Status',
                        render: function(data, type, row) {
                            if (data == 'hadir') {
                                return '<span class="text-success"><i class="bi bi-check-circle-fill"></i> Hadir</span>';
                            } else if (data == 'terlambat') {
                                return '<span class="text-danger"><i class="bi bi-x-circle"></i> Terlambat</span>';
                            } else {
                                return '<span class="text-danger"><i class="bi bi-x-circle"></i> Pulang Cepat</span>';
                            }
                        }
                    }, {
                        data: 'opsi',
                        name: 'Opsi',
                        orderable: false,
                        searchable: false
                    }
                ]
            });
            $('#karyawan_id').on('change', function() {
                $('#laporanKehadiranTable').DataTable().ajax.reload();
            });
            $('#date_record').on('change', function() {
                $('#laporanKehadiranTable').DataTable().ajax.reload();
            });
        });

        /*
         * Refresh table
         */
        function refreshTable() {
            $('#laporanKehadiranTable').DataTable().ajax.reload();
        }
    </script>
@endpush
