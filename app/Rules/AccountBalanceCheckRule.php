<?php

namespace App\Rules;

use App\Models\Account;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class AccountBalanceCheckRule implements ValidationRule
{

    private $accountId;
    public function __construct(int $accountId)
    {
        $this->accountId = $accountId;
    }

    public function validate(string $attribute, $value, Closure $fail)
    {
        $account = Account::find($this->accountId);
        if (blank($account))
            $fail('Account not found');


        if ($value > $account->balance) {
            $fail('Transaction amount is more than account balance');
        }
    }
}
