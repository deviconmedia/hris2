<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShiftStoreRequest extends FormRequest
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
            'nama_shift' => ['required', 'string', 'max:255'],
            'jam_mulai' => ['required', 'date_format:H:i'],
            'jam_batas_mulai' => ['required', 'date_format:H:i'],
            'jam_selesai' => ['required', 'date_format:H:i'],
            'status' => ['required', 'boolean'],
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
            'nama_shift.required' => 'Nama shift wajib diisi',
            'nama_shift.string' => 'Nama shift harus berupa string',
            'nama_shift.max' => 'Nama shift maksimal 255 karakter',
            'jam_mulai.required' => 'Jam mulai wajib diisi',
            'jam_mulai.date_format' => 'Jam mulai harus berupa format H:i',
            'jam_batas_mulai.required' => 'Jam batas mulai wajib diisi',
            'jam_batas_mulai.date_format' => 'Jam batas mulai harus berupa format H:i',
            'jam_selesai.required' => 'Jam selesai wajib diisi',
            'jam_selesai.date_format' => 'Jam selesai harus berupa format H:i',
            'status.required' => 'Status wajib diisi',
            'status.boolean' => 'Status harus berupa boolean',
        ];
    }

}
