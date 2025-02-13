@extends('layouts.authentication_layout.app')

@section('title')
    Login
@endsection

@section('content')
<section class="py-5">
    <div class="container px-5">
        <!-- Contact form-->
        <div class="bg-light rounded-4 py-5 px-4 px-md-5">
            <div class="text-center mb-5">
                <div class="feature bg-primary bg-gradient-primary-to-secondary text-white rounded-3 mb-3">
                    <i class="bi bi-person-fill"></i>
                </div>
                <h1 class="fw-bolder">Masuk ke Akun Anda</h1>
                <p class="lead fw-normal text-muted mb-0">Silahkan login untuk melanjutkan.</p>
            </div>
            <div class="row gx-5 justify-content-center">
                <div class="col-lg-8 col-xl-6">
                    @if ($errors->has('email'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ $errors->first('email') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    <form method="post" id="loginForm">
                        @csrf
                        <div class="form-floating mb-3">
                            <input class="form-control" name="email" id="email" type="email" value="{{ old('email') }}"/>
                            <label for="email">Email</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input class="form-control" name="password" id="password" type="password"/>
                            <label for="password">Kata Sandi</label>
                        </div>
                        <div class="d-grid"><button class="btn btn-primary btn-lg" id="loginBtn" type="submit">Login</button></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('js')
    <script>
         $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#loginForm').on('submit', function(e) {
            e.preventDefault();

            var formData = {
                email: $('#email').val(),
                password: $('#password').val(),
            }

            $.ajax({
                url: '{{ route('authenticate') }}',
                type: 'POST',
                data: formData,
                beforeSend: function() {
                    $('#loginBtn').prop('disabled', true).html(
                        '<i class="fas fa-spinner fa-spin"></i> Mohon tunggu...');
                },
                success: function(response) {
                    if (response.success) {
                        window.location.href = '{{ route('home') }}'
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message,
                            confirmButtonText: 'OK'
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
                    $('#loginBtn').prop('disabled', false).html('<i class="bi bi-floppy-fill"></i> Login');
                }
            });
        });
    </script>
@endpush
