<?php

namespace Kolette\Auth\Tests\Feature\Controllers\V1\CheckController;

use Kolette\Auth\Enums\ErrorCodes;
use Kolette\Auth\Enums\Role;
use Kolette\Auth\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckEmailTest extends TestCase
{
    use RefreshDatabase;

    public function testRegisteredUserCanCheckAccountExistViaEmail(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);

        $user->assignRole(Role::MERCHANT);

        $response = $this->json('POST', '/api/v1/auth/check-email', [
            'email' => $user->email,
            'role' => Role::MERCHANT
        ]);

        $response->assertOk()
            ->assertJsonStructure([
                'data' => ['email'],
            ])
            ->assertJson([
                'data' => ['email' => $user->email],
            ]);

        $this->assertGuest();
    }

    /** @disabled */
    public function unverifiedEmailShouldNotAbleToLogin(): void
    {
        $user = User::factory()->create(['email_verified_at' => null]);

        $this->json('POST', '/api/v1/auth/check-email', [
            'email' => $user->email,
            'role' => Role::MERCHANT
        ])
            ->assertStatus(401)
            ->assertJson([
                'error_code' => ErrorCodes::UNVERIFIED_EMAIL,
            ]);
    }

    public function testUnregisteredEmailShouldReturnNotFound(): void
    {
        $this->json('POST', '/api/v1/auth/check-email', [
            'email' => 'unregistered@email.com',
            'role' => Role::MERCHANT
        ])->assertStatus(404);
    }

    public function testItShouldValidateEmail(): void
    {
        // email is required
        $this->json('POST', '/api/v1/auth/check-email')
            ->assertStatus(422)
            ->assertJsonStructure([
                'errors' => ['email'],
            ]);

        // invalid email format
        $this->json('POST', '/api/v1/auth/check-email', [
            'email' => 'not_an_email',
            'role' => Role::MERCHANT
        ])->assertStatus(422)
            ->assertJsonStructure([
                'errors' => ['email'],
            ]);

        // should not accept a phone_number
        $this->json('POST', '/api/v1/auth/check-email', [
            'email' => '+639453200575',
            'role' => Role::MERCHANT
        ])->assertStatus(422)
            ->assertJsonStructure([
                'errors' => ['email'],
            ]);
    }
}
