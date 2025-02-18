@extends('layouts.admin_layout.app')

@section('title')
    Jenis Cuti
@endsection

@section('subtitle')
    Semua Data Jenis Cuti
@endsection

@section('content')
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="card-header-action d-flex justify-content-end">
                    <a href="{{ route('jenis_cuti.create') }}" class="btn btn-primary rounded-pill"><i class="bi bi-plus-lg"></i>
                        Tambah Baru</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table" id="jenisCutiTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Jenis Cuti</th>
                                    <th>Deskripsi</th>
                                    <th>Jumlah Hari</th>
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
            $('#jenisCutiTable').DataTable({
                processing: true,
                serverside: true,
                ajax: '{!! route('jenis_cuti.getData') !!}',
                columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                }, {
                    data: 'nama_cuti',
                    name: 'Nama Cuti',
                },
                {
                    data: 'deskripsi',
                    name: 'Deskripsi',
                    render: function(data, type, row) {
                        return data ? data : '-';
                    }
                },
                {
                    data: 'jml_hari',
                    name: 'Jumlah Hari',
                    render: function(data, type, row) {
                        return data ? data : '-';
                    }
                },
                {
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
                        url: '/cuti/jenis_cuti/' + id,
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
                                $('#jenisCutiTable').DataTable().ajax.reload();
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
