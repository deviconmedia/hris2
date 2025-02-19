@extends('layouts.admin_layout.app')

@section('title')
    Jabatan
@endsection

@section('subtitle')
    Semua Data Jabatan
@endsection

@section('content')
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="card-header-action d-flex justify-content-end">
                    <a href="{{ route('jabatan.create') }}" class="btn btn-primary rounded-pill"><i class="bi bi-plus-lg"></i>
                        Tambah Baru</a>
                </div>
                <div class="card-body">
                   <div class="table-responsive">
                        <table class="table" id="jabatanTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama Jabatan</th>
                                    <th>Deskripsi</th>
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
                ajax: '{!! route('jabatan.getData') !!}',
                columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                }, {
                    data: 'nama_jabatan',
                    name: 'Nama Jabatan',
                    render: function(data, type, row) {
                        return data.charAt(0).toUpperCase() + data.slice(1).toLowerCase();
                    }
                }, {
                    data: 'deskripsi',
                    name: 'Deskripsi',
                    render: function(data, type, row) {
                        return data ? data : '-';
                    }
                }, {
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
                        url: '/jabatan/hapus/' + id,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                toastr.success(response.message, 'Berhasil', {
                                    timeOut: 3000
                                });
                                $('#jabatanTable').DataTable().ajax.reload(); // Reload tabel DataTables
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
    </script>
@endpush
