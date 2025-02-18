<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NormaCutiStoreRequest extends FormRequest
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
            'jenis_cuti_id' => ['required', 'exists:jenis_cuti,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'karyawan_id.required' => 'Karyawan harus diisi.',
            'karyawan_id.numeric' => 'ID Karyawan harus berupa angka.',
            'karyawan_id.exists' => 'ID Karyawan tidak ditemukan dalam database.',

            'jenis_cuti_id.required' => 'Jenis cuti harus diisi.',
            'jenis_cuti_id.exists' => 'ID Jenis cuti tidak ditemukan dalam database.',
        ];
    }

}
