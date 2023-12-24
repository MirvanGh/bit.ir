<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function testRegisterUser()
    {
        $data = User::factory()->state(fn (array $attributes) => [
            'password_confirmation' => $attributes['password'],
        ])->make()->only('name','email','password','password_confirmation');

        $response = $this->postJson('/api/register',$data);
        $response->assertStatus(201);
    }
}
