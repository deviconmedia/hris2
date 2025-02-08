@extends('layouts.admin_layout.app')

@section('title')
    Ubah Shift
@endsection

@section('subtitle')
    Ubah Data Shift
@endsection

@push('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
@endpush

@section('content')
    <div class="col-12">
        <div class="row">
            <div class="col-8 mx-auto">
                <div class="card">
                    <div class="card-body">
                        <form method="post" id="editForm" data-parsley-validate>
                            <input type="hidden" name="id" id="id" value="{{ $shift->id }}">
                            <div class="form-group mandatory">
                                <label for="nama_shift" class="form-label">Nama Shift</label>
                                <input type="text" name="nama_shift" id="nama_shift" class="form-control"
                                    placeholder="Nama Jabatan" value="{{ $shift->nama_shift }}" required autofocus>
                            </div>
                            <div class="form-group mandatory">
                                <label for="jam_mulai" class="form-label">Jam Mulai</label>
                                <input type="text" class="form-control timepicker" name="jam_mulai" id="jam_mulai" placeholder="Pilih Jam" value="{{ $shift->jam_mulai }}" required>
                            </div>
                            <div class="form-group mandatory">
                                <label for="jam_batas_mulai" class="form-label">Jam Batas Mulai</label>
                                <input type="text" class="form-control timepicker" name="jam_batas_mulai" id="jam_batas_mulai" placeholder="Pilih Jam" value="{{ $shift->jam_batas_mulai }}" required>
                            </div>
                            <div class="form-group mandatory">
                                <label for="jam_selesai" class="form-label">Jam Selesai</label>
                                <input type="text" class="form-control timepicker" name="jam_selesai" id="jam_selesai" placeholder="Pilih Jam" value="{{ $shift->jam_selesai }}" required>
                            </div>
                            <div class="form-group mandatory">
                                <label for="status" class="form-label">Status</label>
                                <select name="status" id="status" class="form-select" required>
                                    <option value="1" {{ $shift->status == 1 ? 'selected' : '' }}>Aktif</option>
                                    <option value="0" {{ $shift->status == 0 ? 'selected' : '' }}>Tidak Aktif</option>
                                </select>
                            </div>
                            <div class="row my-3">
                                <div class="col-12 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary mx-2" id="saveBtn"><i class="bi bi-send"></i> Kirim</button>
                                    <a href="{{ route('shifts.index') }}" class="btn btn-danger"><i class="bi bi-x-lg"></i> Batalkan</a>
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
        document.addEventListener('DOMContentLoaded', () => {
            flatpickr('.timepicker', {
                enableTime: true,
                noCalendar: true,
                dateFormat: "H:i",
                time_24hr: true
            });
        });

         $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#editForm').on('submit', function(e) {
            e.preventDefault();

            var id = $('#id').val();
            var formData = {
                nama_shift: $('#nama_shift').val(),
                jam_mulai: $('#jam_mulai').val(),
                jam_batas_mulai: $('#jam_batas_mulai').val(),
                jam_selesai: $('#jam_selesai').val(),
                status: $('#status').val()
            }

            $.ajax({
                url: `{{ url('/shifts/edit/${id}') }}`,
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
                            window.location.href = '{{ route('shifts.index') }}';
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
