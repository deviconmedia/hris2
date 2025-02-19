@extends('layouts.admin_layout.app')

@section('title')
    Ubah Jenis Cuti
@endsection

@section('subtitle')
    Ubah Jenis Cuti
@endsection

@section('content')
    <div class="col-12">
        <div class="row">
            <div class="col-12 col-md-8 col-lg-8 mx-auto">
                <div class="card">
                    <div class="card-body">
                        <form method="post" id="editForm" data-parsley-validate>
                            <input type="hidden" name="id" id="id" value="{{ $jenisCuti->id }}">
                            <div class="form-group mandatory">
                                <label for="nama_cuti" class="form-label">Jenis Cuti</label>
                                <input type="text" name="nama_cuti" id="nama_cuti" class="form-control"
                                    placeholder="Jenis Cuti" required autofocus value="{{ $jenisCuti->nama_cuti }}">
                            </div>
                            <div class="form-group mandatory">
                                <label for="deskripsi" class="form-label">Deskripsi</label>
                                <textarea name="deskripsi" id="deskripsi" cols="30" rows="2" class="form-control" required placeholder="Deskripsi">{{ $jenisCuti->deskripsi }}</textarea>
                            </div>
                            <div class="form-group mandatory">
                                <label for="jml_hari" class="form-label">Jumlah Hari Maksimal</label>
                                <input type="text" name="jml_hari" id="jml_hari" class="form-control"
                                    placeholder="Jumlah Hari Maksimal" required data-parsley-type="number" value="{{ $jenisCuti->jml_hari }}">
                            </div>
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
    <script>
         $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#editForm').on('submit', function(e) {
            e.preventDefault();
            var id = $('#id').val();
            var formData = {
                nama_cuti: $('#nama_cuti').val(),
                deskripsi: $('#deskripsi').val(),
                jml_hari: $('#jml_hari').val(),
            }

            $.ajax({
                url: `{{ url('/cuti/jenis_cuti/edit/${id}') }}`,
                type: 'PATCH',
                data: formData,
                beforeSend: function() {
                    $('#saveBtn').prop('disabled', true).html(
                        '<i class="fas fa-spinner fa-spin"></i> Sedang Menyimpan...');
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message, 'Berhasil', {
                            timeOut: 3000
                        });

                        setTimeout(() => {
                            window.location.href = '{{ route('jenis_cuti.index') }}';
                        }, 1500);
                    } else {
                        toastr.error(response.message, 'Gagal', {
                            timeOut: 3000
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.log(error);
                    let errorMessage = 'Terjadi kesalahan saat mengirim data!';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }

                    toastr.error(errorMessage, 'Gagal', {
                        timeOut: 3000
                    });
                },

                complete: function() {
                    $('#saveBtn').prop('disabled', false).html('<i class="bi bi-send"></i> Kirim');
                }
            });
        });
    </script>
@endpush
