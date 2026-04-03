<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

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
    public function test_index_returns_10_users_per_page()
    {
        User::factory()->count(12)->create();
        $response = $this->getJson('/api/users');
        $response->assertOk()->assertJsonCount(10, 'data');
    }

    public function test_index_filters_by_id()
    {
        $user = User::factory()->create();
        $response = $this->getJson('/api/users?id=' . $user->id);
        $response->assertOk()->assertJsonCount(1, 'data')->assertJsonPath('data.0.id', $user->id);
    }

    public function test_index_filters_by_name()
    {
        $user = User::factory()->create(['name' => 'jão teste']);
        $response = $this->getJson('/api/users?filter[name]=teste');
        $response->assertOk()->assertJsonCount(1, 'data')->assertJsonPath('data.0.name', 'jão teste');
    }
}