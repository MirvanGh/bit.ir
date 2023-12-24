<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class  Transaction extends Model
{
    use HasFactory;
    const UPDATED_AT = null;
    const CREATED_AT = 'timestamp';
    protected $fillable = ['account_id', 'type', 'amount', 'timestamp'];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function user()
    {
        return $this->hasOneThrough(User::class,Account::class);
    }

}
