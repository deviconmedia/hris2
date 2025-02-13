@extends('layouts.admin_layout.app')

@section('title')
    Norma Cuti
@endsection

@section('subtitle')
    Semua Data Norma Cuti
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('mazer/assets/extensions/choices.js/public/assets/styles/choices.css') }}">
@endpush

@section('content')
    <div class="col-12">
        <div class="alert alert-info">
            <p>Data norma cuti yang ditampilkan adalah norma cuti tahun berjalan untuk semua pegawai.</p>
        </div>
        <div class="card">
            <div class="card-header">
                <div class="card-header-action">
                    <form id="filterForm" method="GET" data-url="{{ route('norma_cuti.getData') }}">
                       <div class="row">
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="karyawan_id" class="form-label">Pilih Karyawan</label>
                                    <select name="karyawan_id" id="karyawan_id" class="form-select choices">
                                        @foreach ($data['staffs'] as $staff)
                                            <option value="{{ $staff->id }}">{{ $staff->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="karyawan_id" class="form-label">Pilih Jenis Cuti</label>
                                    <select name="karyawan_id" id="karyawan_id" class="form-select choices">
                                        @foreach ($data['jenisCuti'] as $cuti)
                                            <option value="{{ $cuti->id }}">{{ $cuti->nama_cuti }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                       </div>
                    </form>
                    <div class="d-flex justify-content-end">
                        <a href="{{ route('norma_cuti.create') }}" class="btn btn-primary rounded-pill"><i class="bi bi-plus-lg"></i>
                            Tambah Baru</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table" id="jenisCutiTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama Karyawan</th>
                                    <th>Jenis Cuti</th>
                                    <th>Cuti Maksimum Tahun ini</th>
                                    <th>Sisa Hari</th>
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
            $('#jenisCutiTable').DataTable({
                processing: true,
                serverside: true,
                ajax: '{!! route('norma_cuti.getData') !!}',
                columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                }, {
                    data: 'karyawan',
                    name: 'Nama Karyawan',
                },
                {
                    data: 'jenis_cuti',
                    name: 'Jenis Cuti',
                    render: function(data, type, row) {
                        return data ? data : '-';
                    }
                },
                {
                    data: 'cuti_max',
                    name: 'Jumlah Hari Maksimum',
                    render: function(data, type, row) {
                        return data ? data : 0;
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
