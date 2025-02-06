@extends('layouts.admin_layout.app')

@section('title')
    Presensi Saya
@endsection

@section('subtitle')
    Rekaman Presensi Saya
@endsection

@section('content')
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                {{-- <div class="card-header-action d-flex justify-content-end">
                    <a href="{{ route('jabatan.create') }}" class="btn btn-primary rounded-pill"><i class="bi bi-plus-lg"></i>
                        Tambah Baru</a>
                </div> --}}
                <div class="card-body">
                   <div class="table-responsive">
                        <table class="table" id="jabatanTable">
                            <thead>
                                <tr>
                                    <th>#</th>
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
            $('#jabatanTable').DataTable({
                processing: true,
                serverside: true,
                ajax: '{!! route('presensi.getData') !!}',
                columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                }, {
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
                            return '<span class="badge bg-success">Hadir</span>';
                        } else if(data == 'terlambat')
                        {
                            return '<span class="badge bg-danger">Terlambat</span>';
                        }else{
                            return '<span class="badge bg-warning">Pulang Cepat</span>';
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
                        url: '/jabatan/hapus/' + id,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire(
                                    'Dihapus!',
                                    'Data berhasil dihapus.',
                                    'success'
                                );
                                $('#jabatanTable').DataTable().ajax.reload(); // Reload tabel DataTables
                            } else {
                                Swal.fire(
                                    'Gagal!',
                                    'Terjadi kesalahan saat menghapus data.',
                                    'error'
                                );
                            }
                        },
                        error: function() {
                            Swal.fire(
                                'Error!',
                                'Terjadi kesalahan. Silakan coba lagi.',
                                'error'
                            );
                        }
                    });
                }
            });
        }
    </script>
@endpush
