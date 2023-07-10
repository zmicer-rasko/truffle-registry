<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_get_token()
    {
        // Given an existing user
        $user = User::factory()->create();

        // A user who knows their credentials can obtain a token
        $this->post('/api/token', [
            'email' => $user->email,
            'password' => 'password'
        ])->assertOk();
    }
}
