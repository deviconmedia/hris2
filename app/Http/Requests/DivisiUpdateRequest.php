<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DivisiUpdateRequest extends FormRequest
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
            'nama_divisi' => ['required', 'string', 'max:100'],
            'email' => ['max:100', Rule::unique('divisi', 'email')->ignore($this->id)],
            'status' => ['boolean'],
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
            'nama_divisi.required' => 'Nama divisi wajib diisi',
            'nama_divisi.string' => 'Nama divisi harus berupa string',
            'nama_divisi.max' => 'Nama divisi maksimal 100 karakter',
            'email.email' => 'Email harus berupa email',
            'email.max' => 'Email maksimal 100 karakter',
            'email.unique' => 'Email sudah terdaftar',
        ];
    }

}
