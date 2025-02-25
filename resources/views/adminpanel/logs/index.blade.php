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
    </script>
@endpush
