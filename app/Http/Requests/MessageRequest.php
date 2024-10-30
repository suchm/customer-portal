<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MessageRequest extends FormRequest
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
            'body' => 'required|string|max:5000',
        ];
    }

    /**
     * Sanitize the input before passing it to the controller.
     */
    protected function passedValidation(): void
    {
        // Sanitize the body field to allow only <br> tags
        $this->merge([
            'body' => strip_tags($this->input('body'), '<br>'),
        ]);
    }
}
