<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function testRegister()
    {
        $userData = [
            "name" => "Test User",
            "email" => "testuser@example.com",
            "password" => "password",
            "password_confirmation" => "password"
        ];

        $response = $this->postJson('/api/auth/register', $userData);

        $response->assertOk();
        $this->assertDatabaseHas('users', ['email' => 'testuser@example.com']);
    }

    public function testLogin()
    {
        $user = User::factory()->create([
            'password' => bcrypt($password = 'i-love-laravel'),
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => $password,
        ]);

        $this->assertAuthenticatedAs($user);
    }
}
