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
                            <td>{{ $data->penyetuju->nama }}</td>
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
                                <a href="{{ asset($data->lampiran) }}" target="_blank">Lihat Lampiran</a>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                <h5 class="text-title">Informasi Persetujuan</h5>
                <small class="text-muted">Belum ada informasi</small>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('mazer/assets/extensions/choices.js/public/assets/scripts/choices.js') }}"></script>
    <script src="{{ asset('mazer/assets/static/js/pages/form-element-select.js') }}"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#createForm').on('submit', function(e) {
            e.preventDefault();

            var formData = new FormData(this);
            $.ajax({
                url: '{{ route('pengajuan_cuti.store') }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $('#saveBtn').prop('disabled', true).html(
                        '<i class="fas fa-spinner fa-spin"></i> Sedang Menyimpan...');
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message,
                            confirmButtonText: 'OK'
                        }).then(() => {
                            window.location.href = '{{ route('norma_cuti.index') }}';
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: response.message,
                            confirmButtonText: 'OK'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.log(error);
                    let errorMessage = 'Terjadi kesalahan saat mengirim data!';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: errorMessage,
                        confirmButtonText: 'OK'
                    });
                },

                complete: function() {
                    $('#saveBtn').prop('disabled', false).html(
                        '<i class="bi bi-send"></i> Kirim');
                }
            });
        });
    </script>
@endpush
