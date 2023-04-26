<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfilePictureRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'avatar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'Nie wybrałeś pliku.',
            'image' => 'Podany plik musi być zdjęciem',
            'mimes' => 'Wybrałeś plik z niedozwolonym rozszerzeniem. Dozowalone rozszerzenia: .jpeg, .jpg, .png',
            'max' => 'Plik może mieć maksymalnie 2048 KB',
        ];
    }
}
