@extends('layouts.admin_layout.app')

@section('title')
    Pengajuan Cuti
@endsection

@section('subtitle')
    Semua Data Pengajuan Cuti
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('mazer/assets/extensions/choices.js/public/assets/styles/choices.css') }}">
@endpush

@section('content')
    <div class="col-12">
        <div class="row my-3">
            <form id="filterForm" method="GET" data-url="{{ route('pengajuan_cuti.getData') }}">
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
                            <label for="jenis_cuti_id" class="form-label">Jenis Cuti</label>
                            <select name="jenis_cuti_id" id="jenis_cuti_id" class="form-select choices">
                                @foreach ($data['jenisCuti'] as $jenisCuti)
                                    <option value="{{ $jenisCuti->id }}">{{ $jenisCuti->nama_cuti }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="card">
            <div class="card-header">
                <div class="float-start">
                    <button class="btn btn-sm btn-secondary" onclick="refreshTable()"><i class="bi bi-arrow-clockwise"></i> Refresh</button>
                </div>
                <div class="card-header-action d-flex justify-content-end">
                    <a href="{{ route('pengajuan_cuti.create') }}" class="btn btn-primary rounded-pill"><i class="bi bi-plus-lg"></i>
                        Tambah Baru</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table" id="pengajuanCutiTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Subjek</th>
                                    <th>Nama</th>
                                    <th>Penyetuju</th>
                                    <th>Mulai Tanggal</th>
                                    <th>Tanggal Selesai</th>
                                    <th>Keterangan</th>
                                    <th>Status</th>
                                    <th>Tanggal Dibuat</th>
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
            $('#pengajuanCutiTable').DataTable({
                processing: true,
                serverside: true,
                ajax: {
                    url: $('#filterForm').data('url'),
                    data: function(d) {
                        d.karyawan_id = $('#karyawan_id').val();
                        d.jenis_cuti_id = $('#jenis_cuti_id').val();
                    }
                },
                columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                }, {
                    data: 'jenis_cuti',
                    name: 'Jenis Cuti',
                },
                {
                    data: 'nama_karyawan',
                    name: 'Nama Karyawan',
                },
                {
                    data: 'penyetuju',
                    name: 'Penyetuju',
                },
                {
                    data: 'tgl_mulai',
                    name: 'Tanggal Mulai',
                },
                {
                    data: 'tgl_selesai',
                    name: 'Tanggal Selesai',
                },
                {
                    data: 'catatan',
                    name: 'Catatan',
                },
                {
                    data: 'status',
                    name: 'Status',
                    render: function(data, type, row) {
                        if (data == 'menunggu konfirmasi') {
                            return '<span class="text-primary"><i class="bi bi-clock-history"></i> Menunggu Konfirmasi</span>';
                        } else if (data == 'disetujui') {
                            return '<span class="text-success"><i class="bi bi-check-circle-fill"></i> Disetujui</span>';
                        }else{
                            return '<span class="text-danger"><i class="bi bi-x-circle"></i>  Ditolak</span>';
                        }
                    }
                },
                {
                    data: 'tgl_pengajuan',
                    name: 'Tanggal Pengajuan',
                },
                {
                    data: 'opsi',
                    name: 'Opsi',
                    orderable: false,
                    searchable: false
                }]
            });
            $('#karyawan_id').on('change', function() {
                $('#pengajuanCutiTable').DataTable().ajax.reload();
            });
            $('#jenis_cuti_id').on('change', function() {
                $('#pengajuanCutiTable').DataTable().ajax.reload();
            });
        });

        // Reject Pengajuan cuti
        function rejectData(id) {
            Swal.fire({
                title: 'Tolak pengajuan cuti ini?',
                text: "Data yang ditolak tidak dapat diubah kembali!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, yakin!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/cuti/pengajuan_cuti/rejected/' + id,
                        type: 'PATCH',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                toastr.success(response.message, 'Berhasil', {
                                    timeOut: 3000
                                });
                                $('#pengajuanCutiTable').DataTable().ajax.reload();
                            } else {
                                toastr.error(response.message, 'Gagal', {
                                    timeOut: 3000
                                });
                            }
                        },
                        error: function() {
                            toastr.error("Ada kesalahan saat menghapus data", 'Gagal', {
                                timeOut: 3000
                            });
                        }
                    });
                }
            });
        }

        // Delete Data
        function deleteData(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/cuti/pengajuan_cuti/' + id,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                toastr.success(response.message, 'Berhasil', {
                                    timeOut: 3000
                                });
                                $('#pengajuanCutiTable').DataTable().ajax.reload();
                            } else {
                                toastr.error(response.message, 'Gagal', {
                                    timeOut: 3000
                                });
                            }
                        },
                        error: function() {
                            toastr.error("Ada kesalahan saat menghapus data", 'Gagal', {
                                timeOut: 3000
                            });
                        }
                    });
                }
            });
        }

          /*
        * Refresh logs table
        */
        function refreshTable() {
            $('#pengajuanCutiTable').DataTable().ajax.reload();
        }
    </script>
@endpush
