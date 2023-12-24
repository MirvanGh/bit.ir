<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;

class UserController extends Controller
{
    public function store(RegisterUserRequest $request)
    {
        return new UserResource(User::create($request->only('name','email','password')));

    }
}
