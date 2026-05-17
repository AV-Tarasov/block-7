<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register(): void
    {
        $response = $this->postJson('/api/v1/register', [
            'name' => 'Alex',
            'email' => 'alex@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response
            ->assertStatus(201)
            ->assertJsonStructure([
                'user',
                'token',
            ]);
    }

    public function test_guest_cannot_access_tasks(): void
    {
        $response = $this->getJson('/api/v1/tasks');

        $response->assertStatus(401);
    }
}
