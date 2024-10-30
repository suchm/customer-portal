<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            'telephone' => ['nullable', 'string', 'max:15'],
            'address_1' => ['required', 'string', 'max:255'],
            'address_2' => ['nullable', 'string', 'max:255'],
            'county' => ['nullable', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'country' => ['required', 'string', 'max:255'],
            'postcode' => ['required', 'string', 'max:10'],
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'name' => trim(strip_tags($this->name)), // Trim whitespace and strip HTML tags
            'last_name' => trim(strip_tags($this->last_name)),
            'email' => strtolower($this->email),     // Convert email to lowercase
            'telephone' => trim(strip_tags($this->telephone)),
            'address_1' => trim(strip_tags($this->address_1)),
            'address_2' => trim(strip_tags($this->address_2)),
            'county' => trim(strip_tags($this->county)),
            'city' => trim(strip_tags($this->city)),
            'country' => trim(strip_tags($this->country)),
            'postcode' => trim(strip_tags($this->postcode)),
        ]);
    }
}
