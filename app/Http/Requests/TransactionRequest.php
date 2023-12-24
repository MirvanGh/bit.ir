<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionRequest extends FormRequest
{
    public function rules()
    {
        return [
            'account_id' => ['required', 'integer', 'exists:accounts,id'],
            'type' => ['required','in:credit,debit'],
            'amount' => ['required', 'integer', 'min:1000'],
        ];
    }

    public function authorize()
    {
        return true;
    }
}
