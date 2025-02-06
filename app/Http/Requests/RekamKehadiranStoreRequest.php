<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RekamKehadiranStoreRequest extends FormRequest
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
            'shift_id' => ['required', 'numeric', 'exists:shifts,id'],
            'latitude'    => ['required'],
            'longitude'    => ['required'],
        ];
    }


    public function messages() : array
    {
        return [
            'shift_id.required' => 'Shift harus diisi',
            'shift_id.numeric' => 'Id Shift harus berupa angka',
            'shift_id.exists' => 'Shift tidak ditemukan',
            'latitude.required' => 'Lokasi tidak ditemukan',
            'longitude.required' => 'Lokasi tidak ditemukan',
        ];
    }

}
