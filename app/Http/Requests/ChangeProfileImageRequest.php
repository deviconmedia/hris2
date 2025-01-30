<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChangeProfileImageRequest extends FormRequest
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
            'image_uri' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:512'], //512 KB
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
            'image_uri.required' => 'Foto harus diisi',
            'image_uri.image' => 'Foto harus berupa gambar',
            'image_uri.mimes' => 'Foto harus berformat jpeg, png, jpg',
            'image_uri.max' => 'Foto maksimal 512 KB',
        ];
    }
}
