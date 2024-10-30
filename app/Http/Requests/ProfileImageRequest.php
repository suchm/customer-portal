<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileImageRequest extends FormRequest
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
            'profile_image' => ['required', 'image', 'mimes:jpg,jpeg,png,gif', 'max:2048'], // max 2048kb or 2mb
        ];
    }

    public function messages()
    {
        return [
            'profile_image.required' => 'A profile image file is required.',
        ];
    }
}
