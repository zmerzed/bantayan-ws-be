<?php

namespace Kolette\Auth\Tests\Feature\Controllers\V1;

use Kolette\Auth\Enums\Role;
use Kolette\Auth\Models\User;
use Kolette\Auth\Notifications\VerifyEmail as VerifyEmailNotification;
use Kolette\Auth\Notifications\VerifyPhoneNumber;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Tests\TestCase;

class RegisterControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function testUserCanRegisterWithEmail(): void
    {
        Notification::fake();
        Notification::assertNothingSent();

        $this->json('POST', '/api/v1/auth/register', [
            'email' => $em = $this->faker->email,
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => Role::MERCHANT
        ])
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'access_token',
                    'token_type',
                    'expires_in',
                    'user' => [
                        'id',
                        'business_name',
                        'email',
                        'created_at',
                        'updated_at',
                    ],
                ],
            ]);

        $this->assertDatabaseHas('users', [
            'email' => $em,
        ]);

        $user = User::whereEmail($em)->first();
        // token must be auto created
        $this->assertNotNull($user->email_verification_code);

        // Notification::assertSentTo(
        //     $user,
        //     VerifyEmailNotification::class,
        //     function ($notification) use ($user) {
        //         $notifier = $notification->toMail($user);
        //         return $notifier->user === $user;
        //     }
        // );
    }

    public function testUserCanRegisterWithPhoneNumber(): void
    {
        Notification::fake();
        Notification::assertNothingSent();

        $this->json('POST', '/api/v1/auth/register', [
            'phone_number' => $pn = '639123456789',
            'password' => $pw = 'password',
            'password_confirmation' => 'password',
            'otp' => '00000',
            'role' => Role::MERCHANT
        ])
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'access_token',
                    'token_type',
                    'expires_in',
                    'user' => [
                        'id',
                        'business_name',
                        'phone_number',
                        'created_at',
                        'updated_at',
                    ],
                ],
            ]);

        $user = User::wherePhoneNumber($pn)->first();

        // token must be auto created
        $this->assertNotNull($user->phone_number_verification_code);

        // Notification::assertSentTo($user, VerifyPhoneNumber::class);
    }

    public function testValidateEmailOnUserRegistration(): void
    {
        $user = User::factory()->create();
        $pw = 'password';

        // no email field
        $this->json('POST', '/api/v1/auth/register', [
            'password' => $pw,
            'password_confirmation' => $pw,
        ])
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => ['email'],
            ]);

        // empty email
        $this->json('POST', '/api/v1/auth/register', [
            'email' => '',
            'password' => $pw,
            'password_confirmation' => $pw,
        ])
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => ['email'],
            ]);

        // invalid email format
        $this->json('POST', '/api/v1/auth/register', [
            'email' => 'not_an_email',
            'password' => $pw,
            'password_confirmation' => $pw,
        ])
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => ['email'],
            ]);

        // invalid email length
        $this->json('POST', '/api/v1/auth/register', [
            'email' => Str::random(256) . '@mail.com',
            'password' => $pw,
            'password_confirmation' => $pw,
        ])
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => ['email'],
            ]);

        // email already existed
        $this->json('POST', '/api/v1/auth/register', [
            'email' => $user->email,
            'password' => $pw,
            'password_confirmation' => $pw,
            'role' => Role::USER
        ])
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => ['email'],
            ]);
    }

    public function testValidatePhoneOnUserRegistration(): void
    {
        $user = User::factory()->create(['phone_number' => '6394512300755', 'email' => null]);
        $pw = 'password';

        // no phone_number field
        $this->json('POST', '/api/v1/auth/register', [
            'password' => $pw,
            'password_confirmation' => $pw,
        ])
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => ['phone_number'],
            ]);

        // empty phone_number
        $this->json('POST', '/api/v1/auth/register', [
            'phone_number' => '',
            'password' => $pw,
            'password_confirmation' => $pw,
        ])
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => ['phone_number'],
            ]);

        // invalid phone_number format
        $this->json('POST', '/api/v1/auth/register', [
            'phone_number' => 'not_a_valid_phone_number',
            'password' => $pw,
            'password_confirmation' => $pw,
        ])
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => ['phone_number'],
            ]);

        // invalid phone_number length
        $this->json('POST', '/api/v1/auth/register', [
            'phone_number' => Str::random(256),
            'password' => $pw,
            'password_confirmation' => $pw,
        ])
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => ['phone_number'],
            ]);

        // phone_number already existed
        $this->json('POST', '/api/v1/auth/register', [
            'phone_number' => $user->phone_number,
            'password' => $pw,
            'password_confirmation' => $pw,
            'role' => Role::USER
        ])
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => ['phone_number'],
            ]);
    }

    /** @skip */
    public function validateFullNameOnUserRegistration(): void
    {
        $user = User::factory()->create();
        $em = $this->faker->email;
        $pw = 'password';

        // no first name field
        $this->json('POST', '/api/v1/auth/register', [
            'email' => $em,
            'password' => $pw,
            'password_confirmation' => $pw,
        ])
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => ['first_name', 'last_name'],
            ]);

        // empty first name
        $this->json('POST', '/api/v1/auth/register', [
            'first_name' => '',
            'last_name' => '',
            'email' => $em,
            'password' => $pw,
            'password_confirmation' => $pw,
        ])
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => ['first_name', 'last_name'],
            ]);

        // invalid first name length
        $this->json('POST', '/api/v1/auth/register', [
            'first_name' => Str::random(256),
            'last_name' => Str::random(256),
            'email' => $em,
            'password' => $pw,
            'password_confirmation' => $pw,
        ])
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => ['first_name', 'last_name'],
            ]);
    }

    public function testValidatePasswordOnUserRegistration(): void
    {
        $em = $this->faker->email;
        $pw = 'password';

        // no password field
        $this->json('POST', '/api/v1/auth/register', [
            'email' => $em,
            'password_confirmation' => $pw,
        ])
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => ['password'],
            ]);

        // empty password
        $this->json('POST', '/api/v1/auth/register', [
            'email' => $em,
            'password' => '',
            'password_confirmation' => $pw,
        ])
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => ['password'],
            ]);

        // invalid password length
        $this->json('POST', '/api/v1/auth/register', [
            'email' => $em,
            'password' => Str::random(7),
            'password_confirmation' => $pw,
        ])
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => ['password'],
            ]);

        // password confirmation fail
        $this->json('POST', '/api/v1/auth/register', [
            'email' => $em,
            'password' => $pw,
            'password_confirmation' => 'not_equal_to_password',
        ])
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => ['password'],
            ]);
    }

    /** @test */
    public function userCanRegisterSameEmailWithDifferentRoles()
    {
        $this->json('POST', '/api/v1/auth/register', [
            'email' => 'duplicate@email.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => Role::MERCHANT
        ])->assertOk();

        $this->json('POST', '/api/v1/auth/register', [
            'email' => 'duplicate@email.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => Role::USER
        ])->assertOk();
    }

    /** @test */
    public function userCantRegisterSameEmailWithSameRoles()
    {
        $this->json('POST', '/api/v1/auth/register', [
            'email' => 'duplicate@email.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => Role::MERCHANT
        ])->assertOk();

        $this->json('POST', '/api/v1/auth/register', [
            'email' => 'duplicate@email.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => Role::MERCHANT
        ])
            ->assertUnprocessable()
            ->assertJsonStructure([
                'message',
                'errors' => ['email'],
            ]);
    }

    /** @test */
    public function userCanRegisterSamePhoneNumberWithSameRoles()
    {
        $this->json('POST', '/api/v1/auth/register', [
            'phone_number' => '639123456789',
            'password' => 'password',
            'password_confirmation' => 'password',
            'otp' => '00000',
            'role' => Role::MERCHANT
        ])->assertOk();

        $this->json('POST', '/api/v1/auth/register', [
            'phone_number' => '639123456789',
            'password' => 'password',
            'password_confirmation' => 'password',
            'otp' => '00000',
            'role' => Role::USER
        ])->assertOk();
    }

    /** @test */
    public function userCantRegisterSamePhoneNumberWithSameRoles()
    {
        $this->json('POST', '/api/v1/auth/register', [
            'phone_number' => '639123456789',
            'password' => 'password',
            'password_confirmation' => 'password',
            'otp' => '00000',
            'role' => Role::MERCHANT
        ])->assertOk();

        $this->json('POST', '/api/v1/auth/register', [
            'phone_number' => '639123456789',
            'password' => 'password',
            'password_confirmation' => 'password',
            'otp' => '00000',
            'role' => Role::MERCHANT
        ])->assertUnprocessable()
            ->assertJsonStructure([
                'message',
                'errors' => ['phone_number'],
            ]);
    }
}
