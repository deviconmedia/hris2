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
                    <form action="{{ route('authenticate') }}" method="post">
                        @csrf
                        <div class="form-floating mb-3">
                            <input class="form-control" name="email" id="email" type="email" value="{{ old('email') }}"/>
                            <label for="email">Email</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input class="form-control" name="password" id="password" type="password"/>
                            <label for="password">Kata Sandi</label>
                        </div>
                        <div class="d-grid"><button class="btn btn-primary btn-lg" id="submitButton" type="submit">Login</button></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
