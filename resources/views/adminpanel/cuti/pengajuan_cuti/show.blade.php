@extends('layouts.admin_layout.app')

@section('title')
    Detail Pengajuan Cuti
@endsection

@section('subtitle')
    Detail Pengajuan Cuti
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('mazer/assets/extensions/choices.js/public/assets/styles/choices.css') }}">
@endpush

@section('content')
    <div class="col-12 col-md-10 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Informasi Umum</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <tr>
                            <th>Subjek</th>
                            <td>{{ $data->jenisCuti->nama_cuti }}</td>
                        </tr>
                        <tr>
                            <th>Nama</th>
                            <td>{{ $data->karyawan->nama }}</td>
                        </tr>
                        <tr>
                            <th>Penyetuju</th>
                            <td>{{ $data->penyetuju->nama ?? 'Tidak diketahui' }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Dibuat</th>
                            <td>{{ date('d-m-Y', strtotime($data->tgl_pengajuan)) }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Mulai</th>
                            <td>{{ date('d-m-Y', strtotime($data->tgl_mulai)) }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Selesai</th>
                            <td>{{ date('d-m-Y', strtotime($data->tgl_selesai)) }}</td>
                        </tr>
                        <tr>
                            <th>Keterangan</th>
                            <td>{{ $data->catatan }}</td>
                        </tr>
                        <tr>
                            <th>Status Pengajuan</th>
                            <td class="text-capitalize text-primary">{{ $data->status }}</td>
                        </tr>
                        <tr>
                            <th>Lampiran</th>
                            <td>
                                <a href="{{ asset($data->lampiran) ?? '#' }}"
                                    target="_blank">{{ $data->lampiran != null ? 'Lihat lampiran' : 'Tidak ada lampiran' }}</a>
                            </td>
                        </tr>
                    </table>
                    @if ($data->status == 'menunggu konfirmasi')
                        <button type="button" class="btn btn-sm btn-success" id="approvedBtn"
                            onclick="approvedData({{ $data->id }})"><i class="bi bi-check-lg"></i> Setujui</button>
                        <button type="button" class="btn btn-sm btn-danger" id="deleteBtn"
                            onclick="deleteData({{ $data->id }})"><i class="bi bi-trash"></i> Hapus</button>
                    @endif
                </div>
            </div>
            <div class="card-footer">
                <h5 class="text-title">Informasi Persetujuan</h5>
                @if ($data->status == 'menunggu konfirmasi')
                    <small class="text-muted">Belum ada informasi</small>
                @elseif($data->status == 'disetujui')
                    <img src="{{ asset('static/img/approved.jpg') }}" alt="stempel" class="mb-2">
                    <table class="table table-striped table-hover">
                        <tr>
                            <th>Status</th>
                            <td class="text-primary text-capitalize">{{ $data->status }}</td>
                        </tr>
                        <tr>
                            <th>Terakhir Diperbarui Oleh:</th>
                            <td>{{ $data->approvedBy->nama }}</td>
                        </tr>
                        <tr>
                            <th>Diperbarui pada:</th>
                            <td>{{ date('d-m-Y, H:i:s', strtotime($data->approved_at)) }}</td>
                        </tr>
                    </table>
                @else
                    <img src="{{ asset('static/img/rejected.JPG') }}" alt="stempel" class="mb-2" style="height: 10em; width: 10em;">
                    <table class="table table-striped table-hover">
                        <tr>
                            <th>Status</th>
                            <td class="text-primary text-capitalize">{{ $data->status }}</td>
                        </tr>
                        <tr>
                            <th>Terakhir Diperbarui Oleh:</th>
                            <td>{{ $data->approvedBy->nama }}</td>
                        </tr>
                        <tr>
                            <th>Diperbarui pada:</th>
                            <td>{{ date('d-m-Y, H:i:s', strtotime($data->approved_at)) }}</td>
                        </tr>
                    </table>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        // Approved Data
        function approvedData(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Anda akan mengubah status pengajuan cuti menjadi 'Disetujui'!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, setujui!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/cuti/pengajuan_cuti/approved/' + id,
                        type: 'PATCH',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                toastr.success(response.message, 'Berhasil', {
                                    timeOut: 3000
                                });
                                setTimeout(() => {
                                    window.location.href =
                                        '{{ route('pengajuan_cuti.index') }}';
                                }, 1000);
                            } else {
                                toastr.error(response.message, 'Gagal', {
                                    timeOut: 3000
                                });
                            }
                        },
                        error: function() {
                            toastr.error("Ada kesalahan saat mengubah data", 'Gagal', {
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
                                setTimeout(() => {
                                    window.location.href =
                                        '{{ route('pengajuan_cuti.index') }}';
                                }, 1000);
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
