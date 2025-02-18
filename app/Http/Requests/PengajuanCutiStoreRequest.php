<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PengajuanCutiStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'karyawan_id' => ['required', 'numeric', 'exists:karyawan,id'],
            'send_to' => ['required', 'numeric', 'exists:karyawan,id'],
            'jenis_cuti_id' => ['required', 'numeric', 'exists:jenis_cuti,id'],
            'tgl_mulai' => ['required', 'date'],
            'tgl_selesai' => ['required', 'date'],
            'catatan' => ['required', 'min:2', 'max:128'],
            'lampiran' => ['nullable', 'mimes:jpg,png,jpeg,pdf', 'max:512'] //512 KB
        ];
    }

    public function messages(): array
    {
        return [
            'karyawan_id.required' => 'Karyawan harus diisi.',
            'karyawan_id.numeric' => 'ID Karyawan harus berupa angka.',
            'karyawan_id.exists' => 'ID Karyawan tidak ditemukan dalam database.',

            'send_to.required' => 'Pilih penyetuju terlebih dahulu.',
            'send_to.numeric' => 'ID penyetuju harus berupa angka.',
            'send_to.exists' => 'ID penyetuju tidak ditemukan dalam database.',

            'jenis_cuti_id.required' => 'Jenis cuti harus diisi.',
            'jenis_cuti_id.numeric' => 'ID Jenis cuti harus berupa angka.',
            'jenis_cuti_id.exists' => 'ID Jenis cuti tidak ditemukan dalam database.',

            'tgl_mulai.required' => 'Tanggal mulai harus diisi.',
            'tgl_mulai.date' => 'Tanggal mulai harus berupa tanggal yang valid.',

            'tgl_selesai.required' => 'Tanggal selesai harus diisi.',
            'tgl_selesai.date' => 'Tanggal selesai harus berupa tanggal yang valid.',

            'catatan.required' => 'Catatan harus diisi.',
            'catatan.min' => 'Catatan harus terdiri dari minimal 2 karakter.',
            'catatan.max' => 'Catatan tidak boleh lebih dari 128 karakter.',

            'lampiran.nullable' => 'Lampiran bersifat opsional.',
            'lampiran.mimes' => 'Lampiran harus berupa file dengan tipe: jpg, png, jpeg, pdf.',
            'lampiran.max' => 'Lampiran tidak boleh lebih dari 512 KB.',
        ];
    }
}
