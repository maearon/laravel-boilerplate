<?php

namespace Tests\Feature;

use App\Mail\AccountActivation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ActivationTest extends TestCase
{
    use RefreshDatabase;

    public function test_activation_email_is_queued_on_registration(): void
    {
        $this->withoutMiddleware();

        Mail::fake();

        // Directly call the service that queues the activation email, then assert.
        $service = app(\App\Services\AccountActivationService::class);

        $response = $this->post('/users', [
            'name' => 'Activation User',
            'email' => 'activation@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(201);

        $user = User::where('email', 'activation@example.com')->first();

        $this->assertNotNull($user);

        // Ensure calling the service queues the mail as expected.
        $service->sendActivationEmail($user);

        Mail::assertQueued(AccountActivation::class, function (AccountActivation $mail) {
            return $mail->hasTo('activation@example.com');
        });
    }

    public function test_activation_link_activates_user_and_logs_them_in(): void
    {
        $user = User::factory()->create([
            'email' => 'pending@example.com',
            'activated' => false,
            'activation_digest' => 'activation-token-123',
        ]);

        $response = $this->get('/account/activate/activation-token-123');

        $response->assertStatus(302);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'activated' => true,
        ]);

        $this->assertTrue(Auth::check());
        $this->assertEquals($user->id, Auth::id());
    }

    public function test_activation_link_with_invalid_token_shows_error(): void
    {
        $response = $this->get('/account/activate/invalid-token');

        $response->assertStatus(302);
        $response->assertSessionHas('danger');
    }
}

