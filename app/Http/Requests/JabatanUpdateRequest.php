<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JabatanUpdateRequest extends FormRequest
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
            'nama_jabatan'  => ['required', 'string', 'max:100'],
            'deskripsi'     => ['required', 'max:100', 'string'],
            'status'        => ['boolean'],
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
            'nama_jabatan.required' => 'Nama divisi wajib diisi',
            'nama_jabatan.string' => 'Nama divisi harus berupa string',
            'nama_jabatan.max' => 'Nama divisi maksimal 100 karakter',
            'deskripsi.required' => 'Deskripsi wajib diisi',
            'deskripsi.string' => 'Deskripsi harus berupa string',
            'deskripsi.max' => 'Deskripsi maksimal 100 karakter',
            'status.boolean' => 'Status harus berupa boolean',
        ];
    }

}
