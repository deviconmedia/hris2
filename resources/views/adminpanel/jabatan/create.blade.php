@extends('layouts.admin_layout.app')

@section('title')
    Tambah Jabatan
@endsection

@section('subtitle')
    Tambah Jabatan Baru
@endsection

@section('content')
    <div class="col-12">
        <div class="row">
            <div class="col-12 col-md-8 mx-auto">
                <div class="card">
                    <div class="card-body">
                        <form method="post" id="createForm" data-parsley-validate>
                            <div class="form-group mandatory">
                                <label for="nama_jabatan" class="form-label">Nama Jabatan</label>
                                <input type="text" name="nama_jabatan" id="nama_jabatan" class="form-control"
                                    placeholder="Nama Jabatan" required autofocus>
                            </div>
                            <div class="form-group mandatory">
                                <label for="deskripsi" class="form-label">Deskripsi</label>
                               <textarea name="deskripsi" id="deskripsi" cols="30" rows="3" class="form-control" placeholder="Masukan Deskripsi" mandatory></textarea>
                            </div>
                            <div class="row my-3">
                                <div class="col-12 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary mx-2" id="saveBtn"><i class="bi bi-send"></i> Kirim</button>
                                    <a href="{{ route('jabatan.index') }}" class="btn btn-danger"><i class="bi bi-x-lg"></i> Batalkan</a>
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

        $('#createForm').on('submit', function(e) {
            e.preventDefault();

            var formData = {
                nama_jabatan: $('#nama_jabatan').val(),
                deskripsi: $('#deskripsi').val(),
            }

            $.ajax({
                url: '{{ route('jabatan.store') }}',
                type: 'POST',
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
                            window.location.href = '{{ route('jabatan.index') }}';
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
