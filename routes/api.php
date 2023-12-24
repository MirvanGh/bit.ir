<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::post('/register',[\App\Http\Controllers\UserController::class,'store']);
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('account',\App\Http\Controllers\AccountController::class)->only('store','show');
    Route::apiResource('transaction',\App\Http\Controllers\TransactionController::class)->only('index','store','show');
});
