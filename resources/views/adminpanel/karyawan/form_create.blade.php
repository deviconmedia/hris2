@extends('layouts.admin_layout.app')

@section('title')
    Tambah Karyawan
@endsection

@section('subtitle')
    Tambah Karyawan Baru
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('mazer/assets/extensions/choices.js/public/assets/styles/choices.css') }}">
@endpush

@section('content')
    <div class="col-12">
        <div class="row">
            <div class="col-10 mx-auto">
                <div class="card">
                    <div class="card-body">
                        <form method="post" id="createForm" class="form" data-parsley-validate>
                            <h6 class="text-primary mt-2"><sup class="text-danger">*</sup> Data Diri</h6>
                            <div class="row">
                                <div class="col-md-6 col-12">
                                    <div class="form-group mandatory">
                                        <label for="nama" class="form-label">Nama Lengkap</label>
                                        <input type="text" name="nama" id="nama" class="form-control"
                                            placeholder="Nama Lengkap" required autofocus>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group mandatory">
                                        <label for="nik" class="form-label">No. Induk Kependudukan (NIK)</label>
                                        <input type="text" name="nik" id="nik" class="form-control"
                                            placeholder="No. Induk Kependudukan (NIK)" required data-parsley-maxlength="16" data-parsley-minlength="16" data-parsley-type="number">
                                        <small id="nikErrorMsg" class="text-danger"></small>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group mandatory">
                                        <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                                        <input type="text" name="tempat_lahir" id="tempat_lahir" class="form-control"
                                            placeholder="Tempat Lahir" required>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group mandatory">
                                        <label for="tgl_lahir" class="form-label">Tanggal Lahir</label>
                                        <input type="date" name="tgl_lahir" id="tgl_lahir" class="form-control"
                                            placeholder="Tanggal Lahir" required>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group mandatory">
                                        <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                                        <select name="jenis_kelamin" id="jenis_kelamin" class="form-select" required>
                                            <option value="" disabled selected hidden>--Pilih--</option>
                                            <option value="L">Laki-laki</option>
                                            <option value="P">Perempuan</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group mandatory">
                                        <label for="tgl_gabung" class="form-label">Tanggal Gabung</label>
                                        <input type="date" name="tgl_gabung" id="tgl_gabung" class="form-control"
                                        placeholder="Tanggal Gabung" required>
                                    </div>
                                </div>
                            </div>
                            <h6 class="text-primary mt-2"><sup class="text-danger">*</sup> Informasi Kontak</h6>
                            <div class="row">
                                <div class="col-md-6 col-12">
                                    <div class="form-group mandatory">
                                        <label for="telepon" class="form-label">Telepon/WhatsApp</label>
                                        <input type="text" name="telepon" id="telepon" class="form-control" placeholder="No. Telepon/WhatsApp" required data-parsley-type="number" data-parsley-minlength="10" data-parsley-maxlength="15">
                                        <small id="phoneErrorMsg" class="text-danger"></small>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group mandatory">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="text" name="email" id="email" class="form-control" placeholder="Email Aktif" required data-parsley-type="email">
                                        <small id="emailErrorMsg" class="text-danger"></small>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group mandatory">
                                        <label for="divisi_id" class="form-label">Divisi</label>
                                        <select name="divisi_id" id="divisi_id" class="form-select choices" required>
                                            <option value="" disabled selected hidden>--Pilih--</option>
                                            @foreach($data['divisi'] as $dvs)
                                                <option value="{{ $dvs->id }}">{{ $dvs->nama_divisi }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group mandatory">
                                        <label for="jabatan_id" class="form-label">Jabatan</label>
                                        <select name="jabatan_id" id="jabatan_id" class="form-select choices" required>
                                            <option value="" disabled selected hidden>--Pilih--</option>
                                            @foreach($data['jabatan'] as $jbt)
                                                <option value="{{ $jbt->id }}">{{ $jbt->nama_jabatan }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="from-group">
                                        <label for="npwp" class="form-label">NPWP <small>(Opsional)</small></label>
                                        <input type="text" name="npwp" id="npwp" class="form-control" placeholder="NPWP" data-parsley-type="number" data-parsley-minlength="15" data-parsley-maxlength="16">
                                        <small id="npwpErrorMsg" class="text-danger"></small>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group mandatory">
                                        <label for="alamat" class="form-label">Alamat Lengkap</label>
                                        <textarea name="alamat" id="alamat" cols="30" rows="3" class="form-control" placeholder="Alamat Lengkap" required></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row my-3">
                                <div class="col-12 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary mx-2" id="saveBtn"><i class="bi bi-floppy-fill"></i> Simpan</button>
                                    <a href="{{ route('karyawan.index') }}" class="btn btn-secondary"><i class="bi bi-x-lg"></i> Kembali</a>
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

        //CEK NIK
        $(document).ready(function() {
            $('#nik').blur(function() {
                const nik = $(this).val();
                if (nik) {
                    $.ajax({
                        url: '/karyawan/cek-nik',
                        type: 'POST',
                        data: {
                            nik: nik,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.exists == false) {
                                $('#nikErrorMsg').text('').hide();
                            } else {
                                $('#nikErrorMsg').text('NIK sudah terdaftar!').show();
                            }
                        },
                        error: function() {
                            $('#nikErrorMsg').text('Terjadi kesalahan!')
                                .show();
                        }
                    });
                }
            });
        });

        //CEK TELEPON
        $(document).ready(function() {
            $('#telepon').blur(function() {
                const telepon = $(this).val();
                if (telepon) {
                    $.ajax({
                        url: '/karyawan/cek-telepon',
                        type: 'POST',
                        data: {
                            telepon: telepon,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.exists == false) {
                                $('#phoneErrorMsg').text('').hide();
                            } else {
                                $('#phoneErrorMsg').text('No. Telepon sudah terdaftar!').show();
                            }
                        },
                        error: function() {
                            $('#phoneErrorMsg').text('Terjadi kesalahan!')
                                .show();
                        }
                    });
                }
            });
        });

        //CEK EMAIL ADDRESS
        $(document).ready(function() {
            $('#email').blur(function() {
                const email = $(this).val();
                if (email) {
                    $.ajax({
                        url: '/karyawan/cek-email',
                        type: 'POST',
                        data: {
                            email: email,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.exists == false) {
                                $('#phoneErrorMsg').text('').hide();
                            } else {
                                $('#phoneErrorMsg').text('Email sudah terdaftar!').show();
                            }
                        },
                        error: function() {
                            $('#phoneErrorMsg').text('Terjadi kesalahan!')
                                .show();
                        }
                    });
                }
            });
        });

         $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#createForm').on('submit', function(e) {
            e.preventDefault();

            var formData = {
                nama: $('#nama').val(),
                nik: $('#nik').val(),
                tempat_lahir: $('#tempat_lahir').val(),
                tgl_lahir: $('#tgl_lahir').val(),
                jenis_kelamin: $('#jenis_kelamin').val(),
                tgl_gabung: $('#tgl_gabung').val(),
                telepon: $('#telepon').val(),
                email: $('#email').val(),
                divisi_id: $('#divisi_id').val(),
                jabatan_id: $('#jabatan_id').val(),
                npwp: $('#npwp').val(),
                alamat: $('#alamat').val()
            };

            $.ajax({
                url: '{{ route('karyawan.store') }}',
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
                            window.location.href = '{{ route('karyawan.index') }}';
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
