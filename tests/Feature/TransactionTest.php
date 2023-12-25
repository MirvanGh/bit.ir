<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    use RefreshDatabase;

    public function testTransaction()
    {
        $user = User::factory()->createOne();
        $account = Account::factory()
            ->state(new Sequence(
                fn (Sequence $sequence) => ['user_id' => $user->id],
            ))->createOne();
        $response = $this->actingAs($user)->postJson(route('transaction.store'),[
            'account_id' => $account->id,
            'type' => 'credit',
            'amount' => 30000,
        ]);
        $response = $this->actingAs($user)->postJson(route('transaction.store'),[
            'account_id' => $account->id,
            'type' => 'debit',
            'amount' => 10000,
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('accounts',[
            'id' => $account->id,
            'balance' => 20000,
        ]);
    }
    public function testShowTransaction()
    {
        $user = User::factory()->createOne();
        $account = Account::factory()
            ->state(new Sequence(
                fn (Sequence $sequence) => ['user_id' => $user->id],
            ))->createOne();
        $response = $this->actingAs($user)->postJson(route('transaction.store'),[
            'account_id' => $account->id,
            'type' => 'credit',
            'amount' => 30000,
        ]);
        $response = $this->actingAs($user)->getJson(route('transaction.show',$response->json('data.id')));
        $response->assertStatus(200);
    }
    public function testListTransaction()
    {
        $user = User::factory()->createOne();
        $account = Account::factory()
            ->state(new Sequence(
                fn (Sequence $sequence) => ['user_id' => $user->id],
            ))->createOne();
        $response = $this->actingAs($user)->postJson(route('transaction.store'),[
            'account_id' => $account->id,
            'type' => 'credit',
            'amount' => 10000,
        ]);
        $response = $this->actingAs($user)->postJson(route('transaction.store'),[
            'account_id' => $account->id,
            'type' => 'credit',
            'amount' => 20000,
        ]);
        $response = $this->actingAs($user)->postJson(route('transaction.store'),[
            'account_id' => $account->id,
            'type' => 'credit',
            'amount' => 30000,
        ]);
        $response = $this->actingAs($user)->getJson(route('transaction.index',['account_id'=>$account->id]));
        $response->assertStatus(200)
            ->assertJsonCount(3,'data');
    }
    public function testInsufficientCreditTransaction()
    {
        $user = User::factory()->createOne();
        $account = Account::factory()
            ->state(new Sequence(
                fn (Sequence $sequence) => ['user_id' => $user->id],
            ))->createOne();
        $response = $this->actingAs($user)->postJson(route('transaction.store'),[
            'account_id' => $account->id,
            'type' => 'credit',
            'amount' => 10000,
        ]);
        $response = $this->actingAs($user)->postJson(route('transaction.store'),[
            'account_id' => $account->id,
            'type' => 'debit',
            'amount' => 20000,
        ]);
        $response->assertStatus(401);
        $this->assertDatabaseHas('accounts',[
            'id' => $account->id,
            'balance' => 10000,
        ]);
    }
}
