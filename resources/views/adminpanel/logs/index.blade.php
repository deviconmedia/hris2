@extends('layouts.admin_layout.app')

@section('title')
    Log Aktivitas
@endsection

@section('subtitle')
    Semua Data Log Aktivitas
@endsection

@section('content')
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <button class="btn btn-sm btn-secondary" onclick="refreshTable()"><i class="bi bi-arrow-clockwise"></i> Refresh</button>
                <button class="btn btn-sm btn-danger" onclick="truncateTable()"><i class="bi bi-trash"></i> Delete Logs</button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table" id="logsTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>User</th>
                                <th>Activity</th>
                                <th>Properties</th>
                                <th>Timestamp</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            $('#logsTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('logs.getData') }}',
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'causer', name: 'causer' },
                    { data: 'description', name: 'description' },
                    { data: 'properties', name: 'properties', render: function(data) {
                        return JSON.stringify(data, null, 2);
                    }},
                    { data: 'created_at', name: 'created_at' }
                ]
            });
        });

        /*
        * Refresh logs table
        */
        function refreshTable() {
            $('#logsTable').DataTable().ajax.reload();
        }

        /*
        * Truncate logs table
        */
        function truncateTable() {
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
                        url: '{{ route('logs.truncate') }}',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                toastr.success(response.message, 'Berhasil', {
                                    timeOut: 3000
                                });
                                $('#logsTable').DataTable().ajax.reload();
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
