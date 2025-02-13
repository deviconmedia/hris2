@extends('layouts.admin_layout.app')

@section('title')
    Tambah Norma Cuti
@endsection

@section('subtitle')
    Tambah Norma Cuti Baru
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('mazer/assets/extensions/choices.js/public/assets/styles/choices.css') }}">
@endpush

@section('content')
    <div class="col-12">
        <div class="row">
            <div class="col-8 mx-auto">
                <div class="card">
                    <div class="card-body">
                        <form method="post" id="createForm" data-parsley-validate>
                            <div class="form-group mandatory">
                                <label for="karyawan_id" class="form-label">Pilih Karyawan</label>
                                <select name="karyawan_id" id="karyawan_id" class="form-select choices">
                                    @foreach ($data['staffs'] as $staff)
                                        <option value="{{ $staff->id }}">{{ $staff->nama }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group mandatory">
                                <label for="jenis_cuti_id" class="form-label">Pilih Jenis Cuti</label>
                                <select name="jenis_cuti_id[]" id="jenis_cuti_id" class="form-select choices multiple-remove" multiple="multiple" data-parsley-required="true">
                                    @foreach ($data['jenisCuti'] as $cuti)
                                        <option value="{{ $cuti->id }}">{{ $cuti->nama_cuti }}</option>
                                    @endforeach
                                </select>
                                <small class="text-muted">*Pilih satu atau lebih jenis cuti.</small>
                            </div>

                            {{-- <div class="form-group mandatory">
                                <label for="jml_hari" class="form-label">Jumlah Hari Maksimal</label>
                                <input type="text" name="jml_hari" id="jml_hari" class="form-control"
                                    placeholder="Jumlah Hari Maksimal" required data-parsley-type="number">
                            </div> --}}
                            <div class="row my-3">
                                <div class="col-12 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary mx-2" id="saveBtn"><i class="bi bi-send"></i> Kirim</button>
                                    <a href="{{ route('jenis_cuti.index') }}" class="btn btn-danger"><i class="bi bi-x-lg"></i> Batalkan</a>
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

            var formData = {
                karyawan_id: $('#karyawan_id').val(),
                jenis_cuti_id: $('#jenis_cuti_id').val(),
            }

            $.ajax({
                url: '{{ route('norma_cuti.store') }}',
                type: 'POST',
                data: formData,
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
                    $('#saveBtn').prop('disabled', false).html('<i class="bi bi-floppy-fill"></i> Simpan');
                }
            });
        });
    </script>
@endpush
