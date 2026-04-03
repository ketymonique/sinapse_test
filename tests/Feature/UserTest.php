<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_user(): void
    {
        $userData = [
            'name' => 'Kethelyn Couto',
            'email' => 'ket@ket.com',
            'password' => 'Senha@123',
            'password_confirmation' => 'Senha@123',
            'phone' => '(11) 99999-9999',
        ];

        $response = $this->postJson('/api/users', $userData);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'data' => [
                         'id',
                         'name',
                         'email',
                         'created_at',
                     ]
                 ]);

        $this->assertDatabaseHas('users', [
            'email' => 'ket@ket.com',
            'name' => 'Kethelyn Couto',
        ]);
    }
}