<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserApiTest extends TestCase
{
    use RefreshDatabase;

    protected function authenticate(): User
    {
        $user = User::factory()->create([
            'activated' => true,
        ]);

        Sanctum::actingAs($user, ['*']);

        return $user;
    }

    public function test_unauthenticated_cannot_access_user_index(): void
    {
        $response = $this->getJson('/api/users');

        $response->assertStatus(401);
    }

    public function test_authenticated_can_list_users(): void
    {
        $this->authenticate();
        User::factory()->count(3)->create();

        $response = $this->getJson('/api/users');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data',
                'links',
                'meta',
            ]);
    }

    public function test_authenticated_can_create_user(): void
    {
        $payload = [
            'name' => 'API User',
            'email' => 'apiuser@example.com',
            'password' => 'password',
        ];

        $response = $this->postJson('/api/register', $payload);

        $response
            ->assertStatus(201)
            ->assertJsonStructure([
                'id',
                'name',
                'email',
                'createdAt',
                'gravatar',
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'apiuser@example.com',
        ]);
    }

    public function test_create_user_validation_errors(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => '',
            'email' => 'not-an-email',
            'password' => '123',
        ]);

        $response
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => ['name', 'email', 'password'],
            ]);
    }

    public function test_authenticated_can_update_user(): void
    {
        $this->authenticate();
        $user = User::factory()->create();

        $response = $this->putJson("/api/users/{$user->id}", [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'password' => 'newpassword',
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'name' => 'Updated Name',
                'email' => 'updated@example.com',
            ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
        ]);
    }

    public function test_authenticated_can_delete_user(): void
    {
        $this->authenticate();
        $user = User::factory()->create();

        $response = $this->deleteJson("/api/users/{$user->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
    }
}

