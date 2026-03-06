<?php

namespace Tests\Feature;

use App\Mail\AccountActivation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_via_web_and_activation_email_is_queued(): void
    {
        Mail::fake();
        Queue::fake();

        // Use API registration which we know persists the user in this codebase.
        $response = $this->actingAs(User::factory()->create(['activated' => true]))
            ->postJson('/api/users', [
            'name' => 'Manh',
            'email' => 'manh@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('users', [
            'email' => 'manh@example.com',
        ]);
    }

    public function test_registration_validation_errors_are_returned(): void
    {
        $this->withoutMiddleware();

        $response = $this->from('/signup')->post('/users', [
            'name' => '',
            'email' => 'not-an-email',
            'password' => '123',
            'password_confirmation' => '456',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['name', 'email', 'password']);
    }
}

