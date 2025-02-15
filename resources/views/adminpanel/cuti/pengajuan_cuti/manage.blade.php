@extends('layouts.admin_layout.app')

@section('title')
    Pengajuan Cuti
@endsection

@section('subtitle')
    Semua Data Pengajuan Cuti
@endsection

@section('content')
    <div class="col-12">
        <div class="row my-3">
            <div class="col-4">
                <div class="form-group">
                    <label for="karyawan_id" class="form-label">Pilih Karyawan</label>
                    <select name="karyawan_id" id="karyawan_id" class="form-select">
                        <option value="">Pilih</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
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
    <script>
        $(document).ready(function() {
            $('#pengajuanCutiTable').DataTable({
                processing: true,
                serverside: true,
                ajax: '{!! route('pengajuan_cuti.getData') !!}',
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
                    data: 'send_to',
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
                                $('#pengajuanCutiTable').DataTable().ajax.reload();
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
