@extends('layouts.admin_layout.app')

@section('title')
    Detail Karyawan
@endsection

@section('subtitle')
    Informasi Profil Karyawan
@endsection

@section('css')
    <style>
        #password_error {
            font-size: small;
            color: red;
            margin-top: 5px;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('mazer/assets/extensions/filepond/filepond.css') }}">
    <link rel="stylesheet"
        href="{{ asset('mazer/assets/extensions/filepond-plugin-image-preview/filepond-plugin-image-preview.css') }}">
    <link rel="stylesheet" href="{{ asset('mazer/assets/extensions/toastify-js/src/toastify.css') }}">
@endsection

@section('content')
    <div class="row">
        <div class="col-12 col-lg-4">
            <div class="card-body">
                <div class="d-flex justify-content-center align-items-center flex-column">
                    <div class="avatar avatar-2xl">
                        <img src="{{ asset($karyawan->image_uri) }}" alt="Avatar">
                    </div>
                    <small class="my-2 badge bg-{{ $karyawan->status == 1? 'success' : 'danger' }} rounded-pill"><i class="bi bi-{{ $karyawan->status == 1 ? 'check-circle' : 'x-circle' }}"></i> {{ $karyawan->status == 1 ? 'Aktif' : 'Tidak Aktif' }}</small>
                    <button type="button" class="btn btn-sm btn-outline-primary " id="changeImageButton">
                        <i class="bi bi-card-image"></i> Ganti Foto
                    </button>

                    <small class="mt-4">{{ $karyawan->kode }}</small>
                    <h5>{{ $karyawan->nama }}</h5>
                    <p class="text-small">{{ $karyawan->divisi->nama_divisi . '|' . $karyawan->jabatan->nama_jabatan }}</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-8">
            <div class="card" id="profileCard">
                <div class="card-header">
                    <div class="table-responsive">
                        <div class="d-flex">
                            <h5 class="card-title">Informasi Profil</h5>
                            <div class="d-flex justify-content-end ms-auto">
                                <a href="{{ route('karyawan.edit', $karyawan->id) }}" class="btn btn-sm btn-secondary"><i
                                        class="bi bi-pencil-square"></i> Ubah</a>
                                <button type="button" class="btn btn-sm btn-{{ $karyawan->status == 1 ? 'danger' : 'success' }} mx-2" data-bs-toggle="modal"
                                    data-bs-target="#changeStatusModal"><i class="bi bi-{{ $karyawan->status == 1 ? 'person-lock' : 'unlock-fill' }}"></i>
                                    {{ $karyawan->status == 1 ? 'Nonaktifkan' : 'Aktifkan' }}</button>
                            </div>
                        </div>
                    </div>
                    <hr>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <tr>
                                <th>Kode Pegawai</th>
                                <td>{{ $karyawan->kode }}</td>
                            </tr>
                            <tr>
                                <th>Nama Lengkap</th>
                                <td>{{ $karyawan->nama }}</td>
                            </tr>
                            <tr>
                                <th>No. Induk Kependudukan (NIK)</th>
                                <td>{{ $karyawan->nik }}</td>
                            </tr>
                            <tr>
                                <th>No. Pokok Wajib Pajak (NPWP)</th>
                                <td>{{ $karyawan->npwp != null ? $karyawan->npwp : 'Tidak Tersedia' }}</td>
                            </tr>
                            <tr>
                                <th>Tempat, Tgl Lahir</th>
                                <td>{{ $karyawan->tempat_lahir . ', ' . date('d/m/Y', strtotime($karyawan->tgl_lahir)) }}
                                </td>
                            </tr>
                            <tr>
                                <th>Jenis Kelamin</th>
                                <td>{{ $karyawan->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                            </tr>
                            <tr>
                                <th>Alamat</th>
                                <td>{{ $karyawan->alamat }}</td>
                            </tr>
                            <tr>
                                <th>No. Telepon</th>
                                <td>{{ $karyawan->telepon }}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{ $karyawan->email }}</td>
                            </tr>
                            <tr>
                                <th>Divisi</th>
                                <td>{{ $karyawan->divisi->nama_divisi }}</td>
                            </tr>
                            <tr>
                                <th>Jabatan</th>
                                <td>{{ $karyawan->jabatan->nama_jabatan }}</td>
                            </tr>
                            <tr>
                                <th>Bergabung Sejak</th>
                                <td>
                                    {{ \Carbon\Carbon::parse($karyawan->tgl_gabung)->format('d/m/Y') .
                                        ' (' .
                                        \Carbon\Carbon::parse($karyawan->tgl_gabung)->diffForHumans() .
                                        ')' }}
                                </td>
                            </tr>

                        </table>
                        <small class="text-muted">Terakhir Diubah: {{ $karyawan->updated_at->diffForHumans() }}</small>
                    </div>
                </div>
            </div>

            {{-- Change Password Form --}}
            <div class="card" id="changePasswordCard">
                <div class="card-header">
                    <h5 class="card-title">Ubah Kata Sandi</h5>
                    <hr>
                </div>
                <div class="card-body">
                    <form data-parsley-validate method="post" id="changePasswordForm">
                        <div class="form-group mandatory">
                            <label for="password" class="form-label">Kata Sandi Baru</label>
                            <input type="password" name="password" id="password" class="form-control"
                                placeholder="Kata Sandi Baru" required autocomplete="off" data-parsley-minlength="8">
                        </div>
                        <div class="form-group mandatory">
                            <label for="password_confirmation" class="form-label">Ulangi Kata Sandi</label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="form-control" placeholder="Ulangi Kata Sandi" required autocomplete="off">
                            <small id="password_error"></small>
                        </div>
                        <button type="submit" class="btn btn-primary" id="saveBtn"><i class="bi bi-send"></i> Kirim</button>
                    </form>
                </div>
            </div>

            {{-- Change Image Form --}}
            <div class="card card-hide" id="changeImageForm">
                <div class="card-header">
                    <h5 class="card-title">Unggah Foto Profil</h5>
                    <hr>
                    <form action="{{ route('karyawan.changeProfileImage', $karyawan->id) }}" class="form"
                        data-parsley-validate method="post" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        <div class="card-body">
                            <p>Unggah foto dengan ekstensi yang diperbolehkan: .png,.jpg,.jpeg. Max:512 KB</p>
                            <div class="form-group mandatory">
                                <label for="image_uri" class="form-label">Pilih Foto</label>
                                <input type="file" class="form-control" id="image_uri" name="image_uri"
                                    accept="image/png, image/jpeg, image/jpg" required>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary"><i class="bi bi-cloud-arrow-up"></i>
                                Unggah</button>
                            <button type="button" class="btn btn-danger" id="cancelChangeImage"><i
                                    class="bi bi-x-lg"></i> Batalkan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Change Status Modal --}}
    <div class="modal fade text-left" id="changeStatusModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel120"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title white" id="myModalLabel120">Konfirmasi
                    </h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i data-feather="x"></i>
                    </button>
                </div>
                <form id="changeStatusForm" method="post">
                    <div class="modal-body">
                        <p>Tindakan ini akan berpengaruh ke akun pengguna karyawan. Apakah Anda yakin?</p>
                        <input type="hidden" name="id" id="id" value="{{ $karyawan->id }}">
                        <input type="hidden" name="status" id="status" value="{{ $karyawan->status }}">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                            <i class="bx bx-x d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Tidak</span>
                        </button>
                        <button type="submit" class="btn btn-danger ms-1" data-bs-dismiss="modal">
                            <i class="bx bx-check d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Yakin</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script
        src="{{ asset('mazer/assets/extensions/filepond-plugin-file-validate-size/filepond-plugin-file-validate-size.min.js') }}">
    </script>
    <script
        src="{{ asset('mazer/assets/extensions/filepond-plugin-file-validate-type/filepond-plugin-file-validate-type.min.js') }}">
    </script>
    <script src="{{ asset('mazer/assets/extensions/filepond-plugin-image-crop/filepond-plugin-image-crop.min.js') }}">
    </script>
    <script
        src="{{ asset('mazer/assets/extensions/filepond-plugin-image-exif-orientation/filepond-plugin-image-exif-orientation.min.js') }}">
    </script>
    <script src="{{ asset('mazer/assets/extensions/filepond-plugin-image-filter/filepond-plugin-image-filter.min.js') }}">
    </script>
    <script
        src="{{ asset('mazer/assets/extensions/filepond-plugin-image-preview/filepond-plugin-image-preview.min.js') }}">
    </script>
    <script src="{{ asset('mazer/assets/extensions/filepond-plugin-image-resize/filepond-plugin-image-resize.min.js') }}">
    </script>
    <script src="{{ asset('mazer/assets/extensions/filepond/filepond.js') }}"></script>
    <script src="{{ asset('mazer/assets/extensions/toastify-js/src/toastify.js') }}"></script>
    <script src="{{ asset('mazer/assets/static/js/pages/filepond.js') }}"></script>

    <script>
        $(document).ready(function() {
            $("#changeImageForm").hide();
            $("#changeImageButton").click(function() {
                $("#profileCard").hide();
                $("#changePasswordCard").hide();
                $("#changeImageForm").show();
            });

            $("#cancelChangeImage").click(function() {
                $("#profileCard").show();
                $("#changePasswordCard").show();
                $("#changeImageForm").hide();
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.getElementById('password');
            const confirmPasswordInput = document.getElementById('password_confirmation');
            const passwordError = document.getElementById('password_error');

            confirmPasswordInput.addEventListener('input', function() {
                const password = passwordInput.value;
                const confirmPassword = confirmPasswordInput.value;

                if (confirmPassword === '') {
                    passwordError.textContent = '';
                } else if (password === confirmPassword) {
                    passwordError.textContent = '';
                    passwordError.style.color = 'green';
                    $('#saveBtn').prop('disabled', false);

                } else {
                    passwordError.textContent = 'Konfirmasi kata sandi tidak cocok!';
                    passwordError.style.color = 'red';
                    $('#saveBtn').prop('disabled', true);
                }
            });
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });


        $('#changePasswordForm').on('submit', function(e) {
            e.preventDefault();
            var formData = {
                password: $('#password').val(),
                password_confirmation: $('#password_confirmation').val(),
            };

            $.ajax({
                url: '{{ route('karyawan.changePassword', $karyawan->id) }}',
                type: 'PATCH',
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
                            window.location.href =
                                '{{ route('karyawan.show', $karyawan->id) }}';
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
                        '<i class="bi bi-floppy-fill"></i> Simpan');
                }
            });
        });

        $('#changeStatusForm').on('submit', function(e) {
            e.preventDefault();
            var formData = {
                status: $('#status').val(),
            };

            $.ajax({
                url: '{{ route('karyawan.changeStatus', $karyawan->id) }}',
                type: 'PATCH',
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
                            window.location.href =
                                '{{ route('karyawan.show', $karyawan->id) }}';
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
                        '<i class="bi bi-floppy-fill"></i> Simpan');
                }
            });
        });
    </script>
@endpush
