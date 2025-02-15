<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JenisCutiStoreRequest extends FormRequest
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
            'nama_cuti'     => ['required', 'string', 'min:2', 'max:50', 'unique:jenis_cuti,nama_cuti'],
            'deskripsi'     => ['required', 'string', 'min:4', 'max:100'],
            'jml_hari'      => ['required', 'numeric'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_cuti.required' => 'Nama cuti wajib diisi.',
            'nama_cuti.string' => 'Nama cuti harus berupa teks.',
            'nama_cuti.min' => 'Nama cuti harus terdiri dari minimal 2 karakter.',
            'nama_cuti.max' => 'Nama cuti tidak boleh lebih dari 50 karakter.',
            'nama_cuti.unique' => 'Nama cuti sudah ada, silakan pilih nama lain.',

            'deskripsi.required' => 'Deskripsi wajib diisi.',
            'deskripsi.string' => 'Deskripsi harus berupa teks.',
            'deskripsi.min' => 'Deskripsi harus terdiri dari minimal 4 karakter.',
            'deskripsi.max' => 'Deskripsi tidak boleh lebih dari 100 karakter.',

            'jml_hari.required' => 'Jumlah hari wajib diisi.',
            'jml_hari.numeric' => 'Jumlah hari harus berupa angka.',
        ];
    }

}
