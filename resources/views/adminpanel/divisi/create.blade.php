@extends('layouts.admin_layout.app')

@section('title')
    Tambah Divisi
@endsection

@section('subtitle')
    Tambah Divisi Baru
@endsection

@section('content')
    <div class="col-12">
        <div class="row">
            <div class="col-12 col-md-8 mx-auto">
                <div class="card">
                    <div class="card-body">
                        <form method="post" id="createForm" data-parsley-validate>
                            <div class="form-group mandatory">
                                <label for="nama_divisi" class="form-label">Nama Divisi</label>
                                <input type="text" name="nama_divisi" id="nama_divisi" class="form-control"
                                    placeholder="Nama Divisi" required autofocus>
                            </div>
                            <div class="form-group">
                                <label for="email" class="form-label">Email <small>(Opsional)</small></label>
                                <input type="email" name="email" id="email" class="form-control"
                                    placeholder="Email Divisi" data-parsley-type="email">
                            </div>
                            <div class="row my-3">
                                <div class="col-12 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary mx-2" id="saveBtn"><i
                                            class="bi bi-send"></i> Kirim</button>
                                    <a href="{{ route('divisi.index') }}" class="btn btn-danger"><i class="bi bi-x-lg"></i>
                                        Batalkan</a>
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
                nama_divisi: $('#nama_divisi').val(),
                email: $('#email').val(),
            }

            $.ajax({
                url: '{{ route('divisi.store') }}',
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
                            window.location.href = '{{ route('divisi.index') }}';
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
                    $('#saveBtn').prop('disabled', false).html(
                        '<i class="bi bi-send"></i> Kirim');
                }
            });
        });
    </script>
@endpush
