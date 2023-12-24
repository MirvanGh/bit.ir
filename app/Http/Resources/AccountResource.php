<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Account */
class AccountResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'balance' => $this->balance ?? 0,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'transactions' => TransactionResource::collection($this->whenLoaded('transactions')),
        ];
    }
}
