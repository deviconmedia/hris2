@extends('layouts.admin_layout.app')

@section('title')
    Ubah Jabatan
@endsection

@section('subtitle')
    Ubah Data Jabatan
@endsection

@section('content')
    <div class="col-12">
        <div class="row">
            <div class="col-8 mx-auto">
                <div class="card">
                    <div class="card-body">
                        <form method="post" id="editForm" data-parsley-validate>
                            <input type="hidden" name="id" id="id" value="{{ $jabatan->id }}">
                            <div class="form-group mandatory">
                                <label for="nama_jabatan" class="form-label">Nama Jabatan</label>
                                <input type="text" name="nama_jabatan" id="nama_jabatan" class="form-control"
                                    placeholder="Nama Jabatan" value="{{ $jabatan->nama_jabatan }}" required autofocus>
                            </div>
                            <div class="form-group mandatory">
                                <label for="deskripsi" class="form-label">Deskripsi <small>(Opsional)</small></label>
                               <textarea name="deskripsi" id="deskripsi" cols="30" rows="3" class="form-control" placeholder="Masukan Deskripsi" mandatory>{{ $jabatan->deskripsi }}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="status" class="form-label">Status</label>
                                <select name="status" id="status" class="form-select">
                                    <option value="1" {{ $jabatan->status == 1 ? 'selected' : '' }}>Aktif</option>
                                    <option value="0" {{ $jabatan->status == 0 ? 'selected' : '' }}>Tidak Aktif</option>
                                </select>
                            </div>
                            <div class="row my-3">
                                <div class="col-12 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary mx-2" id="saveBtn"><i class="bi bi-floppy-fill"></i> Simpan</button>
                                    <a href="{{ route('jabatan.index') }}" class="btn btn-secondary"><i class="bi bi-x-lg"></i> Kembali</a>
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
                nama_jabatan: $('#nama_jabatan').val(),
                deskripsi: $('#deskripsi').val(),
                status: $('#status').val(),
            }

            $.ajax({
                url: `{{ url('/jabatan/edit/${id}') }}`,
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
                            window.location.href = '{{ route('jabatan.index') }}';
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
