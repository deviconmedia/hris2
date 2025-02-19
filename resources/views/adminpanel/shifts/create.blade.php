@extends('layouts.admin_layout.app')

@section('title')
    Tambah Shift
@endsection

@section('subtitle')
    Tambah Shift Baru
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
                        <form method="post" id="createForm" data-parsley-validate>
                            <div class="form-group mandatory">
                                <label for="nama_shift" class="form-label">Nama Shift</label>
                                <input type="text" name="nama_shift" id="nama_shift" class="form-control"
                                    placeholder="Nama Jabatan" required autofocus>
                            </div>
                            <div class="form-group mandatory">
                                <label for="jam_mulai" class="form-label">Jam Mulai</label>
                                <input type="text" class="form-control timepicker" name="jam_mulai" id="jam_mulai" placeholder="Pilih Jam" required>
                            </div>
                            <div class="form-group mandatory">
                                <label for="jam_batas_mulai" class="form-label">Jam Batas Mulai</label>
                                <input type="text" class="form-control timepicker" name="jam_batas_mulai" id="jam_batas_mulai" placeholder="Pilih Jam" required>
                            </div>
                            <div class="form-group mandatory">
                                <label for="jam_selesai" class="form-label">Jam Selesai</label>
                                <input type="text" class="form-control timepicker" name="jam_selesai" id="jam_selesai" placeholder="Pilih Jam" required>
                            </div>
                            <div class="form-group mandatory">
                                <label for="status" class="form-label">Status</label>
                                <select name="status" id="status" class="form-select" required>
                                    <option value="" disabled selected hidden>--Pilih--</option>
                                    <option value="1">Aktif</option>
                                    <option value="0">Tidak Aktif</option>
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

        $('#createForm').on('submit', function(e) {
            e.preventDefault();

            var formData = {
                nama_shift: $('#nama_shift').val(),
                jam_mulai: $('#jam_mulai').val(),
                jam_batas_mulai: $('#jam_batas_mulai').val(),
                jam_selesai: $('#jam_selesai').val(),
                status: $('#status').val()
            }

            $.ajax({
                url: '{{ route('shifts.store') }}',
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
                            window.location.href = '{{ route('shifts.index') }}';
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
