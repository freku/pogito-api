<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FirstPasswordRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'Podaj hasła',
            'string' => 'Pola muszą być tekstem',
            'min' => 'Hasło musi mieć minimum :min znaków',
            'confirmed' => 'Hasła nie są takie same!'
        ];
    }

    // TODO: add throttling

}
