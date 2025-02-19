@extends('layouts.admin_layout.app')

@section('title')
    Ubah Data Karyawan
@endsection

@section('subtitle')
    Ubah Data Karyawan
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('mazer/assets/extensions/choices.js/public/assets/styles/choices.css') }}">
@endpush

@section('content')
    <div class="col-12">
        <div class="row">
            <div class="col-12 col-md-10 mx-auto">
                <div class="card">
                    <div class="card-body">
                        <form method="post" id="createForm" class="form" data-parsley-validate>
                            <h6 class="text-primary mt-2"><sup class="text-danger">*</sup> Data Diri</h6>
                            <div class="row">
                                <input type="hidden" name="id" id="id" value="{{ $karyawan->id }}">
                                <div class="col-md-6 col-12">
                                    <div class="form-group mandatory">
                                        <label for="nama" class="form-label">Nama Lengkap</label>
                                        <input type="text" name="nama" id="nama" class="form-control"
                                            placeholder="Nama Lengkap" required autofocus value="{{ $karyawan->nama }}">
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group mandatory">
                                        <label for="nik" class="form-label">No. Induk Kependudukan (NIK)</label>
                                        <input type="text" name="nik" id="nik" class="form-control"
                                            placeholder="No. Induk Kependudukan (NIK)" required data-parsley-maxlength="16" data-parsley-minlength="16" data-parsley-type="number" value="{{ $karyawan->nik }}">
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group mandatory">
                                        <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                                        <input type="text" name="tempat_lahir" id="tempat_lahir" class="form-control"
                                            placeholder="Tempat Lahir" required value="{{ $karyawan->tempat_lahir }}">
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group mandatory">
                                        <label for="tgl_lahir" class="form-label">Tanggal Lahir</label>
                                        <input type="date" name="tgl_lahir" id="tgl_lahir" class="form-control"
                                            placeholder="Tanggal Lahir" required value="{{ $karyawan->tgl_lahir }}">
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group mandatory">
                                        <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                                        <select name="jenis_kelamin" id="jenis_kelamin" class="form-select" required>
                                            <option value="L" {{ $karyawan->jenis_kelamin == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                            <option value="P" {{ $karyawan->jenis_kelamin == 'P' ? 'selected' : '' }}>Perempuan</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group mandatory">
                                        <label for="tgl_gabung" class="form-label">Tanggal Gabung</label>
                                        <input type="date" name="tgl_gabung" id="tgl_gabung" class="form-control"
                                        placeholder="Tanggal Gabung" required value="{{ $karyawan->tgl_gabung }}">
                                    </div>
                                </div>
                            </div>
                            <h6 class="text-primary mt-2"><sup class="text-danger">*</sup> Informasi Kontak</h6>
                            <div class="row">
                                <div class="col-md-6 col-12">
                                    <div class="form-group mandatory">
                                        <label for="telepon" class="form-label">Telepon/WhatsApp</label>
                                        <input type="text" name="telepon" id="telepon" class="form-control" placeholder="No. Telepon/WhatsApp" required data-parsley-type="number" data-parsley-minlength="10" data-parsley-maxlength="15" value="{{ $karyawan->telepon }}">
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group mandatory">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="text" name="email" id="email" class="form-control" placeholder="Email Aktif" required data-parsley-type="email" value="{{ $karyawan->email }}">
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group mandatory">
                                        <label for="divisi_id" class="form-label">Divisi</label>

                                        <select name="divisi_id" id="divisi_id" class="form-select choices" required>
                                            @isset($data['divisi'])
                                                @foreach ($data['divisi'] as $dvs)
                                                    <option value="{{ $dvs->id }}" {{ $karyawan->divisi_id == $dvs->id ? 'selected' : '' }}>
                                                        {{ $dvs->nama_divisi }}
                                                    </option>
                                                @endforeach
                                            @else
                                                <option value="" disabled>Data divisi tidak tersedia</option>
                                            @endisset
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-group mandatory">
                                        <label for="jabatan_id" class="form-label">Jabatan</label>
                                        <select name="jabatan_id" id="jabatan_id" class="form-select choices" required>
                                            @isset($data['jabatan'])
                                                @foreach ($data['jabatan'] as $jbt)
                                                    <option value="{{ $jbt->id }}" {{ $karyawan->jabatan_id == $jbt->id ? 'selected' : '' }}>
                                                        {{ $jbt->nama_jabatan }}
                                                    </option>
                                                @endforeach
                                            @else
                                                <option value="" disabled>Data jabatan tidak tersedia</option>
                                            @endisset
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="from-group">
                                        <label for="npwp" class="form-label">NPWP <small>(Opsional)</small></label>
                                        <input type="text" name="npwp" id="npwp" class="form-control" placeholder="NPWP" data-parsley-type="number" data-parsley-minlength="15" data-parsley-maxlength="16" value="{{ $karyawan->npwp }}">
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group mandatory">
                                        <label for="alamat" class="form-label">Alamat Lengkap</label>
                                        <textarea name="alamat" id="alamat" cols="30" rows="3" class="form-control" placeholder="Alamat Lengkap" required>
                                            {{ $karyawan->alamat }}
                                        </textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row my-3">
                                <div class="col-12 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary mx-2" id="saveBtn"><i class="bi bi-send"></i> Kirim</button>
                                    <a href="{{ route('karyawan.show', $karyawan->id) }}" class="btn btn-danger"><i class="bi bi-x-lg"></i> Batalkan</a>
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
            var id = $('#id').val();
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
                url: `{{ url('karyawan/edit/${id}') }}`,
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
                            window.location.href = '{{ route('karyawan.show', $karyawan->id) }}';
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
