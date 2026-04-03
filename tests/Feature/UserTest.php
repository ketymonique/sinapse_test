<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class UserTest extends TestCase
{
    use RefreshDatabase;

    // success 
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

    public function test_show_user()
    {
        $user = User::factory()->create();

        $response = $this->getJson('/api/users/' . $user->id);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => ['id', 'name', 'email', 'phone', 'created_at']
                ])
                ->assertJsonPath('data.id', $user->id);
    }

    public function test_user_not_found()
    {
        $response = $this->getJson('/api/users/99999');
        $response->assertStatus(404);
    }

    public function test_update_user()
    {
        $user = User::factory()->create();
        $newData = [
            'name' => 'Nome Atualizado',
            'email' => 'atualizado@email.com',
            'password' => 'NovaSenha@123',
            'password_confirmation' => 'NovaSenha@123',
            'phone' => '(21) 98888-7777',
        ];

        $response = $this->putJson('/api/users/' . $user->id, $newData);

        $response->assertStatus(200)
                ->assertJsonPath('data.name', 'Nome Atualizado')
                ->assertJsonPath('data.email', 'atualizado@email.com')
                ->assertJsonPath('data.phone', '(21) 98888-7777');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Nome Atualizado',
            'email' => 'atualizado@email.com',
            'phone' => '(21) 98888-7777',
        ]);
    }

    public function test_update_user_not_found()
    {
        $response = $this->putJson('/api/users/99999', [
            'name' => 'Qualquer',
            'email' => 'qualquer@email.com',
            'password' => 'Senha@123',
            'password_confirmation' => 'Senha@123',
            'phone' => '(11) 99999-9999',
        ]);
        $response->assertStatus(404);
    }

    public function test_update_validates_email()
    {
        $user1 = User::factory()->create(['email' => 'primeiro@email.com']);
        $user2 = User::factory()->create(['email' => 'segundo@email.com']);

        $response = $this->putJson('/api/users/' . $user2->id, [
            'name' => 'Teste',
            'email' => 'primeiro@email.com',
            'password' => 'Senha@123',
            'password_confirmation' => 'Senha@123',
            'phone' => '(11) 99999-9999',
        ]);
        $response->assertStatus(422)
                ->assertJsonValidationErrors(['email']);

        $response = $this->putJson('/api/users/' . $user2->id, [
            'name' => 'Teste',
            'email' => 'segundo@email.com',
            'password' => 'Senha@123',
            'password_confirmation' => 'Senha@123',
            'phone' => '(11) 99999-9999',
        ]);
        $response->assertStatus(200);
    }

    public function test_soft_delete_user()
    {
        $user = User::factory()->create();

        $response = $this->deleteJson('/api/users/' . $user->id);
        $response->assertStatus(204); 

        $this->assertSoftDeleted('users', ['id' => $user->id]);
    }

    public function test_deleted_not_in_index()
    {
        $user = User::factory()->create();
        $this->deleteJson('/api/users/' . $user->id);

        $response = $this->getJson('/api/users');
        $response->assertJsonCount(0, 'data');
    }

    public function test_deleted_show_404()
    {
        $user = User::factory()->create();
        $this->deleteJson('/api/users/' . $user->id);

        $response = $this->getJson('/api/users/' . $user->id);
        $response->assertStatus(404);
    }

    // error
    public function test_store_validation_errors()
    {
        $response = $this->postJson('/api/users', [
            'name' => '',
            'email' => 'not-an-email',
            'password' => 'weak',
            'password_confirmation' => 'different',
            'phone' => 'invalid',
        ]);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['name', 'email', 'password', 'phone']);
    }

    public function test_update_validation_errors()
    {
        $user = User::factory()->create();

        $response = $this->putJson('/api/users/' . $user->id, [
            'name' => '',
            'email' => 'not-email',
            'password' => 'weak',
            'password_confirmation' => 'different',
            'phone' => '123',
        ]);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['name', 'email', 'password', 'phone']);
    }
}