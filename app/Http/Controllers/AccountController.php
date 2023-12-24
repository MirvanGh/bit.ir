<?php

namespace App\Http\Controllers;

use App\Http\Requests\AccountRequest;
use App\Http\Resources\AccountResource;
use App\Models\Account;

class AccountController extends Controller
{

    public function store()
    {
        return new AccountResource(Account::create(['user_id' => auth()->user()->id]));
    }

    public function show(Account $account)
    {
        return new AccountResource($account);
    }

}
