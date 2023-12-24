<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccountTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateNewAccount()
    {
        $user = User::factory()->createOne();
        $response = $this->actingAs($user)->postJson(route('account.store'));

        $response->assertStatus(201);
    }
    public function testAccountBalance()
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
        $response = $this->actingAs($user)->getJson(route('account.show',$account->id));
        $response->assertStatus(200)
            ->assertJson(['data'=>['balance'=>20000]]);

    }
}
