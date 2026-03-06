<?php

namespace Tests\Unit;

use App\Mail\AccountActivation;
use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Services\AccountActivationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Tests\TestCase;

class ActivationServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_creates_user_with_activation_digest(): void
    {
        $service = app(AccountActivationService::class);

        $user = $service->register([
            'name' => 'Service User',
            'email' => 'service@example.com',
            'password' => 'password',
        ]);

        $this->assertInstanceOf(User::class, $user);
        $this->assertNotNull($user->activation_digest);
        // Ensure the stored password is not the raw value (i.e. it was hashed).
        $this->assertNotEquals('password', $user->password);
        $this->assertDatabaseHas('users', [
            'email' => 'service@example.com',
        ]);
    }

    public function test_send_activation_email_queues_mailable(): void
    {
        Mail::fake();

        $user = User::factory()->create([
            'email' => 'queue@example.com',
        ]);

        $service = app(AccountActivationService::class);

        $service->sendActivationEmail($user);

        Mail::assertQueued(AccountActivation::class, function (AccountActivation $mail) use ($user) {
            return $mail->hasTo($user->email);
        });
    }

    public function test_activate_with_valid_token_updates_user_and_logs_in(): void
    {
        $user = User::factory()->create([
            'activation_digest' => 'valid-token',
            'activated' => false,
        ]);

        /** @var AccountActivationService $service */
        $service = app(AccountActivationService::class);

        $activatedUser = $service->activate('valid-token');

        $this->assertNotNull($activatedUser);
        $this->assertTrue($activatedUser->activated);
        $this->assertNull($activatedUser->activation_digest);
        $this->assertTrue(Auth::check());
        $this->assertEquals($user->id, Auth::id());
    }

    public function test_activate_with_invalid_token_returns_null(): void
    {
        $user = User::factory()->create([
            'activation_digest' => Str::random(60),
            'activated' => false,
        ]);

        /** @var AccountActivationService $service */
        $service = app(AccountActivationService::class);

        $result = $service->activate('non-existent-token');

        $this->assertNull($result);
        $this->assertFalse($user->fresh()->activated);
    }
}

