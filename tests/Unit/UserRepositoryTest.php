<?php

namespace Tests\Unit;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_paginate_activated_returns_only_activated_users(): void
    {
        $repo = new UserRepository();

        User::factory()->count(2)->create(['activated' => true]);
        User::factory()->count(2)->create(['activated' => false]);

        $result = $repo->paginateActivated(10);

        $this->assertCount(2, $result->items());
        foreach ($result->items() as $user) {
            $this->assertTrue($user->activated);
        }
    }

    public function test_find_by_email_returns_correct_user(): void
    {
        $user = User::factory()->create(['email' => 'repo@example.com']);
        $repo = new UserRepository();

        $found = $repo->findByEmail('repo@example.com');

        $this->assertNotNull($found);
        $this->assertEquals($user->id, $found->id);
    }

    public function test_find_by_activation_digest_returns_correct_user(): void
    {
        $user = User::factory()->create(['activation_digest' => 'digest-token']);
        $repo = new UserRepository();

        $found = $repo->findByActivationDigest('digest-token');

        $this->assertNotNull($found);
        $this->assertEquals($user->id, $found->id);
    }

    public function test_create_update_and_delete_user(): void
    {
        $repo = new UserRepository();

        $user = $repo->create([
            'name' => 'Repo User',
            'email' => 'repouser@example.com',
            'password' => 'password',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'repouser@example.com',
        ]);

        $repo->update($user, ['name' => 'Updated Repo User']);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Repo User',
        ]);

        $repo->delete($user);

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
    }
}

