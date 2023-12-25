<?php

namespace App\Http\Controllers;

use App\Exceptions\InsufficientCreditException;
use App\Http\Requests\TransactionRequest;
use App\Http\Resources\TransactionResource;
use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Contracts\Cache\LockTimeoutException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $account = Account::where('user_id',auth()->user()->id)->where('id',$request->get('account_id'))->first();
        abort_if(blank($account),403,'not found');
        return TransactionResource::collection(Transaction::whereAccountId($request->get('account_id'))->paginate());
    }

    public function store(TransactionRequest $request)
    {

        $account = Account::find($request->get('account_id'));
        abort_if($account->user_id != auth()->user()->id,401);

        $lock = Cache::lock($request->get('account_id'), 10);

        try {
            $lock->block(5);

            if($request->get('type') == 'debit'){
                throw_if($account->balance < $request->get('amount'),InsufficientCreditException::class);
            }

            return new TransactionResource(Transaction::create($request->validated()));
        } catch (LockTimeoutException $e) {
            return response()->json([
                'message'   => 'locked on other transaction'
            ],432);
        } finally {
            $lock?->release();
        }
    }

    public function show(Transaction $transaction)
    {
        return new TransactionResource($transaction);
    }

}
