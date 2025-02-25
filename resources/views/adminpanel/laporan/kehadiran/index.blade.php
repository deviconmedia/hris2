@extends('layouts.admin_layout.app')

@section('title')
    Presensi Karyawan
@endsection

@section('subtitle')
    Rekaman Presensi Karyawan
@endsection

@section('content')
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="card-header">
                    <button class="btn btn-sm btn-secondary" onclick="refreshTable()"><i class="bi bi-arrow-clockwise"></i> Refresh</button>
                </div>
                <div class="card-body">
                   <div class="table-responsive">
                        <table class="table" id="rekamanPresensiTable">
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
    <script>
        $(document).ready(function() {
            $('#rekamanPresensiTable').DataTable({
                processing: true,
                serverside: true,
                ajax: '{!! route('laporan_kehadiran.getData') !!}',
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
                        } else if(data == 'terlambat')
                        {
                            return '<span class="text-danger"><i class="bi bi-x-circle"></i> Terlambat</span>';
                        }else{
                            return '<span class="text-danger"><i class="bi bi-x-circle"></i> Pulang Cepat</span>';
                        }
                    }
                }, {
                    data: 'opsi',
                    name: 'Opsi',
                    orderable: false,
                    searchable: false
                }]
            });
        });

         /*
        * Refresh table
        */
        function refreshTable() {
            $('#rekamanPresensiTable').DataTable().ajax.reload();
        }
    </script>
@endpush
