<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterUserRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => ['required'],
            'email' => ['required', 'email','unique:users,email', 'max:254'],
            'password' => ['required','confirmed'],
        ];
    }
    protected function passedValidation()
    {
        $this->merge([
            'password' => \Hash::make($this->get('password')),
        ]);
    }
}
