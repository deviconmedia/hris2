<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KaryawanStoreRequest extends FormRequest
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
            'nama' => ['required', 'string', 'max:100'],
            'nik' => ['required', 'numeric', 'unique:karyawan,nik'],
            'email' => ['required', 'email', 'max:100', 'unique:karyawan,email'],
            'telepon' => ['required', 'numeric', 'unique:karyawan,telepon'],
            'tempat_lahir' => ['required', 'string', 'max:100'],
            'tgl_lahir' => ['required', 'date'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'npwp' => ['nullable', 'numeric', 'unique:karyawan,npwp'],
            'alamat' => ['required', 'string', 'max:255'],
            'tgl_gabung' => ['required', 'date'],
            'divisi_id' => ['required', 'numeric', 'exists:divisi,id'],
            'jabatan_id' => ['required', 'numeric', 'exists:jabatan,id'],
            'status' => ['nullable', 'in:1,0'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama harus diisi',
            'name.string' => 'Nama harus berupa string',
            'name.max' => 'Nama maksimal 100 karakter',
            'nik.required' => 'NIK harus diisi',
            'nik.numeric' => 'NIK harus berupa angka',
            'nik.min' => 'NIK minimal 16 karakter',
            'nik.max' => 'NIK maksimal 16 karakter',
            'nik.unique' => 'NIK sudah terdaftar',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Email harus valid',
            'email.max' => 'Email maksimal 100 karakter',
            'email.unique' => 'Email sudah terdaftar',
            'telepon.required' => 'Telepon harus diisi',
            'telepon.numeric' => 'Telepon harus berupa angka',
            'telepon.min' => 'Telepon minimal 10 karakter',
            'telepon.max' => 'Telepon maksimal 15 karakter',
            'telepon.unique' => 'Telepon sudah terdaftar',
            'tempat_lahir.required' => 'Tempat lahir harus diisi',
            'tempat_lahir.string' => 'Tempat lahir harus berupa string',
            'tempat_lahir.max' => 'Tempat lahir maksimal 100 karakter',
            'tgl_lahir.required' => 'Tanggal lahir harus diisi',
            'tgl_lahir.date' => 'Tanggal lahir harus berupa tanggal',
            'jenis_kelamin.required' => 'Jenis kelamin harus diisi',
            'jenis_kelamin.in' => 'Jenis kelamin harus L atau P',
            'npwp.numeric' => 'NPWP harus berupa angka',
            'npwp.min' => 'NPWP minimal 15 karakter',
            'npwp.max' => 'NPWP maksimal 16 karakter',
            'npwp.unique' => 'NPWP sudah terdaftar',
            'alamat.required' => 'Alamat harus diisi',
            'alamat.string' => 'Alamat harus berupa string',
            'alamat.max' => 'Alamat maksimal 255 karakter',
            'tgl_gabung.required' => 'Tanggal gabung harus diisi',
            'tgl_gabung.date' => 'Tanggal gabung harus berupa tanggal',
            'divisi_id.required' => 'Divisi harus diisi',
            'divisi_id.numeric' => 'Divisi harus berupa angka',
            'divisi_id.exists' => 'Divisi tidak ditemukan',
            'jabatan_id.required' => 'Jabatan harus diisi',
            'jabatan_id.numeric' => 'Jabatan harus berupa angka',
            'jabatan_id.exists' => 'Jabatan tidak ditemukan',
            'status.in' => 'Status harus 1 atau 0',
        ];
    }

}
