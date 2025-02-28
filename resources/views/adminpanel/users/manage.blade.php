@extends('layouts.admin_layout.app')

@section('title')
    Pengguna
@endsection

@section('subtitle')
    Semua Data Pengguna
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
                        <table class="table" id="usersTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Peran</th>
                                    <th>Login Terakhir</th>
                                    <th>Aktif?</th>
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
    <script src="{{ asset('static/js/moment.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#usersTable').DataTable({
                processing: true,
                serverside: true,
                ajax: '{!! route('users.getData') !!}',
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    }, {
                        data: 'name',
                        name: 'Nama Lengkap',
                        render: function(data, type, row) {
                            return data.charAt(0).toUpperCase() + data.slice(1).toLowerCase();
                        }
                    },
                    {
                        data: 'email',
                        name: 'email',
                    },
                    {
                        data: 'role_name',
                        name: 'role_name',
                    },
                    {
                        data: 'last_logged',
                        name: 'last_logged',
                        render: function(data, type, row) {
                        if (data) {
                            moment.locale('id');

                            return moment(data).fromNow();
                        }
                        return 'Tidak Ada Data';
                    }
                    },
                    {
                        data: 'is_active',
                        name: 'is_active',
                    }
                ]
            });
        });

        // Handle switch change
        $('#usersTable').on('change', '.toggle-active', function() {
            let userId = $(this).data('id');
            let isActive = $(this).is(':checked') ? 1 : 0;

            $.ajax({
                url: '/modul_sistem/users/' + userId + '/toggle-active',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    is_active: isActive
                },
                success: function(response) {
                    toastr.success(response.message, 'Berhasil', {
                        timeOut: 3000
                    });
                    $('#usersTable').DataTable().ajax.reload();
                },
                error: function(xhr) {
                    toastr.error('Terjadi kesalahan saat mengubah data', 'Gagal', {
                        timeOut: 3000
                    });
                }
            });
        });
    </script>
@endpush
