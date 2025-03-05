<?php

namespace Kolette\Auth\Tests\Feature\Controllers\V1\CheckController;

use Kolette\Auth\Enums\ErrorCodes;
use Kolette\Auth\Enums\Role;
use Kolette\Auth\Enums\UsernameType;
use Kolette\Auth\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckUsernameTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function registeredUserCanCheckAccountExistViaEmail()
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $user->syncRoles(Role::MERCHANT);

        $res = $this->json('POST', '/api/v1/auth/check-username', [
            'username' => $user->email,
            'role' => Role::MERCHANT
        ]);

        $res->assertOk()
            ->assertJsonStructure([
                'data' => ['username'],
            ])
            ->assertJson([
                'data' => ['username' => $user->email],
            ]);

        $this->assertGuest();
    }

    /** @test */
    public function registeredUserCanCheckAccountExistViaPhoneNumber()
    {
        $user = User::factory()->create([
            "phone_number" => '+63945' . rand(1000000, 9999999),
            'phone_number_verified_at' => now(),
            'primary_username' => UsernameType::PHONE_NUMBER,
        ]);
        $user->syncRoles(Role::MERCHANT);

        $this->json('POST', '/api/v1/auth/check-username', [
            'username' => $user->phone_number,
            'role' => Role::MERCHANT,
        ])
            ->assertOk()
            ->assertJsonStructure([
                'data' => ['username'],
            ])
            ->assertJson([
                'data' => ['username' => $user->phone_number],
            ]);

        $this->assertGuest();
    }

    /** @disabled */
    public function unverifiedPhoneNumberShouldNotAbleToLogin()
    {
        $user = User::factory()->create([
            "phone_number" => '+63945' . rand(1000000, 9999999),
            'phone_number_verified_at' => null,
        ]);

        $this->json('POST', '/api/v1/auth/check-username', [
            'username' => $user->phone_number,
            'role' => Role::MERCHANT
        ])
            ->assertStatus(401)
            ->assertJson([
                'error_code' => ErrorCodes::UNVERIFIED_PHONE_NUMBER,
            ]);
    }

    /** @disabled */
    public function unverifiedEmailShouldNotAbleToLogin()
    {
        $user = User::factory()->create(['email_verified_at' => null]);

        $this->json('POST', '/api/v1/auth/check-username', [
            'username' => $user->email,
            'role' => Role::MERCHANT
        ])
            ->assertStatus(401)
            ->assertJson([
                'error_code' => ErrorCodes::UNVERIFIED_EMAIL,
            ]);
    }

    /** @test */
    public function unregisteredUsernameShouldReturnNotFound()
    {
        // test unregistered number
        $this->json('POST', '/api/v1/auth/check-username', [
            'username' => 'invalid@email.com',
            'role' => Role::MERCHANT
        ])->assertStatus(404);

        // test unregistered number
        $this->json('POST', '/api/v1/auth/check-username', [
            'username' => '+639451111111',
            'role' => Role::MERCHANT
        ])->assertStatus(404);
    }

    /** @test */
    public function usernameIsRequired()
    {
        $this->json('POST', '/api/v1/auth/check-username')
            ->assertStatus(422)
            ->assertJsonStructure([
                'errors' => ['username'],
            ]);
    }

    /** @test */
    public function validateEmailIfExistInSameRole()
    {
        $user = User::factory()->create(['email' => 'test@email.com']);
        $user->syncRoles(Role::MERCHANT);

        // test unregistered number
        $this->json('POST', '/api/v1/auth/check-username', [
            'username' => 'test@email.com',
            'role' => Role::MERCHANT
        ])->assertOk();
    }

    /** @test */
    public function validateEmailIfNotExistInDifferentRole()
    {
        $user = User::factory()->create(['email' => 'test@email.com']);
        $user->syncRoles(Role::MERCHANT);

        // test unregistered number
        $this->json('POST', '/api/v1/auth/check-username', [
            'username' => 'test@email.com',
            'role' => Role::USER
        ])->assertStatus(404);
    }

    /** @test */
    public function validatePhoneNumberIfExistInSameRole()
    {
        $user = User::factory()->create([
            "phone_number" => '+63945' . rand(1000000, 9999999),
            'phone_number_verified_at' => now(),
            'primary_username' => UsernameType::PHONE_NUMBER,
        ]);
        $user->syncRoles(Role::MERCHANT);

        // test unregistered number
        $this->json('POST', '/api/v1/auth/check-username', [
            'username' => $user->phone_number,
            'role' => Role::MERCHANT
        ])->assertOk();
    }
}
