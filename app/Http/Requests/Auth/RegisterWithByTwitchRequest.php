<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterWithByTwitchRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'between:4,20', 'unique:users', 'alpha_num', 'bail'],
        ];

        $twitchCbData = $this->session()->get('twitch_cb_user');

        if ($twitchCbData['email'] === null) {
            $rules['email'] = ['required', 'string', 'email', 'max:255', 'unique:users'];
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'name.required' => 'Nick jest wymagany.',
            'email.required' => 'Email jest wymagany',
            'email' => 'Podaj email.',
            'max' => 'Pole nie może być większe niż :max znaków',
            'string' => 'Pole musi być tekstem.',
            'between' => 'Nick musi byc pomiędzi 4 a 20 znakami.',
            'unique' => "':input' jest już zajęte!",
            'alpha_num' => 'Nick musi się składać tylko z liter i cyfr.',
        ];
    }
}
