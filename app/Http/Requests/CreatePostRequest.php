<?php

namespace App\Http\Requests;

use App\Rules\IsValidClipUrl;
use Illuminate\Foundation\Http\FormRequest;

class CreatePostRequest extends FormRequest
{
    public function prepareForValidation()
    {
        $this->merge(['tags' => preg_split('/\s+/', trim($this->get('tags')))]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'tytul' => ['required', 'max:255'],
            'link' => ['required', 'max:255', new IsValidClipUrl],
            'tags' => ['array', 'max:5'],
            'tags.*' => ['max:12', 'starts_with:#', 'min:2', 'distinct', 'regex:/^[#][a-zA-Z]+$/u'],
        ];
    }

    public function messages(): array
    {
        return [
            'tytul.required' => 'Pole :attribute jest wymagane!',
            'link.required' => 'Pole :attribute jest wymagane!',
            'tytul.max' => 'Pole :attribute nie może być dłuższe niż 255 znaków!',
            'link.max' => 'Pole :attribute nie może być dłuższe niż 255 znaków!',
            'tags.*.max' => 'Tagi mogą mieć maksymalnie 12 znaków!',
            'tags.*.starts_with' => 'Tagi muszą zaczynać się z #. np. #Fail',
            'tags.*.min' => 'Bledy tag, przyklad: #Fail',
            'tags.*.distinct' => 'Tagi nie mogą się powtarzać!',
            'tags.*.regex' => 'Niepoprawny format tagu! Przyklad: #nazwa gdzie nazwa może składać się tylko z liter.',
            'tags.max' => 'Dozwolone jest maksymalnie 5 tagów!',
        ];
    }
}
