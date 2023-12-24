<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AccountRequest extends FormRequest
{
    public function rules()
    {
        return [
            'user_id' => ['required', 'integer'],
            'balance' => ['required', 'integer'],
        ];
    }

    public function authorize()
    {
        return true;
    }
}
