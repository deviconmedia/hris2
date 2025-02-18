@extends('layouts.admin_layout.app')

@section('title')
    Tambah Pengajuan Cuti
@endsection

@section('subtitle')
    Tambah Pengajuan Cuti Baru
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('mazer/assets/extensions/choices.js/public/assets/styles/choices.css') }}">
    <link rel="stylesheet" href="{{ asset('mazer/assets/extensions/filepond/filepond.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('mazer/assets/extensions/filepond-plugin-image-preview/filepond-plugin-image-preview.css') }}"> --}}
@endpush

@section('content')
    <div class="col-12">
        <div class="row">
            <div class="col-8 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <strong>Perhatian!</strong> Tanggal pengajuan cuti maksimal H-1 sebelum tanggal mulai cuti.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="post" id="createForm" data-parsley-validate enctype="multipart/form-data">
                            <div class="form-group mandatory">
                                <label for="karyawan_id" class="form-label">Pilih Karyawan</label>
                                <select name="karyawan_id" id="karyawan_id" class="form-select choices" required>
                                    @foreach ($data['staffs'] as $staff)
                                        <option value="{{ $staff->id }}" {{ $data['currentStaffId'] == $staff->id ? 'selected' : '' }}>{{ $staff->nama }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group mandatory">
                                <label for="jenis_cuti_id" class="form-label">Pilih Jenis Cuti</label>
                                <select name="jenis_cuti_id" id="jenis_cuti_id" class="form-select choices"
                                    data-parsley-required="true">
                                    @foreach ($data['normaCuti'] as $normaCuti)
                                        <option value="{{ $normaCuti->jenisCuti->id }}">{{ $normaCuti->jenisCuti->nama_cuti }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group mandatory">
                                <label for="send_to" class="form-label">Serahkan Ke</label>
                                <select name="send_to" id="send_to" class="form-select choices" required>
                                    @foreach ($data['staffs'] as $staff)
                                        <option value="{{ $staff->id }}">{{ $staff->nama }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group mandatory">
                                <label for="tgl_mulai" class="form-label">Tanggal Mulai</label>
                                <input type="date" name="tgl_mulai" id="tgl_mulai" class="form-control" required>
                            </div>
                            <div class="form-group mandatory">
                                <label for="tgl_selesai" class="form-label">Tanggal Selesai</label>
                                <input type="date" name="tgl_selesai" id="tgl_selesai" class="form-control" required>
                            </div>
                            <div class="form-group mandatory">
                                <label for="catatan" class="form-label">Tambahkan Catatan</label>
                                <textarea name="catatan" id="catatan" cols="30" rows="3" class="form-control" required
                                    placeholder="Tambahkan catatan"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="lampiran" class="form-label">Lampiran</label>
                                <input type="file" class="basic-filepond" name="lampiran" id="lampiran">
                            </div>
                            <div class="row my-3">
                                <div class="col-12 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary mx-2" id="saveBtn"><i
                                            class="bi bi-send"></i> Kirim</button>
                                    <a href="{{ route('pengajuan_cuti.index') }}" class="btn btn-danger"><i
                                            class="bi bi-x-lg"></i> Batalkan</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('mazer/assets/extensions/filepond-plugin-file-validate-size/filepond-plugin-file-validate-size.min.js') }}"></script>
    <script src="{{ asset('mazer/assets/extensions/filepond-plugin-file-validate-type/filepond-plugin-file-validate-type.min.js') }}"></script>
    <script src="{{ asset('mazer/assets/extensions/filepond-plugin-image-crop/filepond-plugin-image-crop.min.js') }}"></script>
    <script src="{{ asset('mazer/assets/extensions/filepond-plugin-image-exif-orientation/filepond-plugin-image-exif-orientation.min.js') }}"></script>
    <script src="{{ asset('mazer/assets/extensions/filepond-plugin-image-filter/filepond-plugin-image-filter.min.js') }}"></script>
    <script src="{{ asset('mazer/assets/extensions/filepond-plugin-image-preview/filepond-plugin-image-preview.min.js') }}"></script>
    <script src="{{ asset('mazer/assets/extensions/filepond-plugin-image-resize/filepond-plugin-image-resize.min.js') }}"></script>
    <script src="{{ asset('mazer/assets/extensions/filepond/filepond.js') }}"></script>
    {{-- <script src="{{ asset('mazer/assets/extensions/toastify-js/src/toastify.js') }}"></script> --}}
    <script src="{{ asset('mazer/assets/static/js/pages/filepond.js') }}"></script>
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
