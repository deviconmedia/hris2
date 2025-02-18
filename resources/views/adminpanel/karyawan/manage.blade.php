@extends('layouts.admin_layout.app')

@section('title')
    Karyawan
@endsection

@section('subtitle')
    Semua Data Karyawan
@endsection

@section('content')
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="card-header-action d-flex justify-content-end">
                    <a href="{{ route('karyawan.create') }}" class="btn btn-primary rounded-pill"><i class="bi bi-plus-lg"></i>
                        Tambah Baru</a>
                </div>
                <div class="card-body">
                   <div class="table-responsive">
                        <table class="table" id="karyawanTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Kode</th>
                                    <th>Nama Lengkap</th>
                                    <th>Telepon</th>
                                    <th>Jenis Kelamin</th>
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
            $('#karyawanTable').DataTable({
                processing: true,
                serverside: true,
                ajax: '{!! route('karyawan.getData') !!}',
                columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'kode',
                    name: 'Kode',
                    render: function(data, type, row) {
                        return data.charAt(0).toUpperCase() + data.slice(1).toLowerCase();
                    }
                },
                {
                    data: 'nama',
                    name: 'Nama Lengkap',
                    render: function(data, type, row) {
                        return data.charAt(0).toUpperCase() + data.slice(1).toLowerCase();
                    }
                },
                {
                    data: 'telepon',
                    name: 'Telepon',
                    render: function(data, type, row) {
                        return data ? data : '-';
                    }
                },
                {
                    data: 'jenis_kelamin',
                    name: 'Jenis Kelamin',
                    render: function(data, type, row) {
                       if(data == 'L') {
                           return 'Laki-laki';
                       } else {
                           return 'Perempuan';
                       }
                    }
                },
                {
                    data: 'status',
                    name: 'Status',
                    render: function(data, type, row) {
                        if (data == 1) {
                            return '<span class="text-success"><i class="bi bi-check-circle-fill"></i> Aktif</span>';
                        } else {
                            return '<span class="text-danger"><i class="bi bi-x-circle"></i> Tidak Aktif</span>';
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
                        url: '/karyawan/hapus/' + id,
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
                                $('#karyawanTable').DataTable().ajax.reload(); // Reload tabel DataTables
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
