<?php

namespace Kolette\Auth\Models;

use App\Models\Traits\ManagesOrder;
use Kolette\Auth\Enums\Role;
use Kolette\PushNotification\Models\Traits\InteractsWithDeviceTokens;
use Kolette\Payment\Models\Traits\ManagesStripeCards;
use Kolette\Payment\Models\Traits\ManagesStripeConnectAccount;
use Laravel\Cashier\Billable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Kolette\Auth\Enums\UsernameType;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Kolette\Auth\Factories\UserFactory;
use Illuminate\Database\Eloquent\Builder;
use Kolette\Auth\Observers\UserObserver;
use Kolette\Auth\Support\ValidatesPhone;
use Kolette\Auth\Models\Traits\HasAvatar;
use Kolette\Auth\Services\IsStripeCustomer;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Kolette\Auth\Support\BypassCodeValidator;
use App\Models\Traits\InteractsWithRewardSystems;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Kolette\Reporting\Models\Traits\CanBeReported;
use Kolette\Sms\Notifications\Channels\SmsChannel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Traits\InteractsWithBusinessInformation;
use App\Models\Traits\InteractsWithCustomerInformation;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Kolette\Auth\Models\Traits\ManagesOneTimePassword;
use Kolette\Auth\Models\Traits\InteractsWithChangeRequest;
use Kolette\Auth\Models\Traits\InteractsWithVerificationToken;
use Kolette\Auth\Models\Interfaces\Notifiable as NotifiableInterface;

class User extends Authenticatable implements
   // MustVerifyEmail,
    //HasMedia
    // NotifiableInterface,
    \Kolette\Auth\Contracts\User
{
    // use HasRoles;
    // use HasFactory;
    // use HasAvatar;
    // use Notifiable;
    // use HasApiTokens;
    // use ManagesOneTimePassword {
    //     hasValidOneTimePassword as traitHasValidOneTimePassword;
    // }
    // use InteractsWithChangeRequest;
    // use InteractsWithVerificationToken;
    // use ValidatesPhone;
    // use BypassCodeValidator;
    // use HasApiTokens;
    // use InteractsWithVerificationToken;
    // use InteractsWithRewardSystems;
    // use InteractsWithBusinessInformation;
    // use InteractsWithCustomerInformation;
    // use CanBeReported;
    // use CanPostProducts;
    // use AbilityToCart;
    // use SoftDeletes;
    // use InteractsWithDeviceTokens;
    // use ManagesStripeCards;
    // use ManagesStripeConnectAccount;
    // use ManagesOrder;
    // use Billable;

    /**
     * The default guard to use
     * This will be used by laravel permission package to determine the guard to use
     *
     * @var string
     */
    protected $guard_name = 'api';

    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'email_verification_code',
        'phone_number_verification_code',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_number_verified_at' => 'datetime',
        'onboarded_at' => 'datetime',
        'lock_reward_system' => 'boolean',
        'last_lock_reward_system_at' => 'boolean'
    ];

    protected static function newFactory(): UserFactory
    {
        return UserFactory::new();
    }

    protected static function booted(): void
    {
        static::observe(UserObserver::class);

        static::addGlobalScope('blocked', function (Builder $query) {
            $query->whereNull('blocked_at');
        });
    }

    public function resolveRouteBinding($value, $field = null): ?Model
    {
        return $this->where($field ?? $this->getRouteKeyName(), $value)
            ->withBlocked()
            ->first();
    }

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function passwordReset(): HasOne
    {
        return $this->hasOne(PasswordReset::class, 'user_id');
    }


    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeSearch(Builder $query, string $search): void
    {
        $query->where(function ($query) use ($search) {
            $query->where('first_name', 'LIKE', "%$search%")
                ->orWhere('last_name', 'LIKE', "%$search%")
                ->orWhere('email', 'LIKE', "%$search%")
                ->orWhere('phone_number', 'LIKE', "%$search%")
                ->orWhereHas('businessInformation', function ($query) use ($search) {
                    $query->where('name', 'like', "%$search%");
                    $query->orWhere('address', 'like', "%$search%");
                });
        });
    }

    public function scopeWithBlocked(Builder $query): void
    {
        $query->withoutGlobalScope('blocked');
    }

    public function scopeOnlyBlocked(Builder $query): void
    {
        $query->withoutGlobalScope('blocked');
        $query->whereNotNull('blocked_at');
    }

    public function scopeOnlyNonOnBoarded(Builder $query): void
    {
        $query->whereNull('onboared_at');
    }

    public function scopeOnlyOnboarded(Builder $query): void
    {
        $query->whereNotNull('onboarded_at');
    }

    public function scopeHasUsername(Builder $query, string $username): void
    {
        $query->where('email', $username);
        $query->orWhere('phone_number', $this->cleanPhoneNumber($username));
    }

    public function scopeNonGuest(Builder $query): void
    {
        $query->whereHas('roles', fn($innerQuery) => $innerQuery->whereIn('name', [Role::USER]));
    }

    public function scopeMerchant(Builder $query): void
    {
        $query->whereHas('roles', fn($innerQuery) => $innerQuery->whereIn('name', [Role::MERCHANT]));
    }

    public function scopeCustomer(Builder $query): void
    {
        $query->whereHas('roles', fn($innerQuery) => $innerQuery->whereIn('name', [Role::USER]));
    }

    /*
    |--------------------------------------------------------------------------
    | Mutator methods
    |--------------------------------------------------------------------------
    */

    /**
     * Remove the plus (+) sign for every phone number
     */
    public function setPhoneNumberAttribute(?string $value): void
    {
        $this->attributes['phone_number'] = $value ? $this->cleanPhoneNumber($value) : $value;
    }

    /*
    |--------------------------------------------------------------------------
    | Accessor methods
    |--------------------------------------------------------------------------
    */

    /**
     * Converts the first character of each word in user's full name to uppercase
     */
    public function getFullNameAttribute(): string
    {
        return ucwords(implode(' ', [$this->first_name, $this->last_name]));
    }


    /**
     * Get verified phone number or email
     */
    public function getVerifiedAccountAttribute(): ?string
    {
        return $this->isEmailVerified() ? $this->email : $this->phone_number;
    }

    /*
    |--------------------------------------------------------------------------
    | Helper methods
    |--------------------------------------------------------------------------
    */

    public function isEmailVerified(): bool
    {
        return filled($this->email_verified_at);
    }

    public function isValidEmailVerificationCode(?string $code): bool
    {
        /** on debug mode, allow bypass for token validation */
        if ($this->isUsingBypassCode($code)) {
            return true;
        }

        return $code === $this->email_verification_code;
    }

    public function isValidPhoneVerificationCode(?string $code): bool
    {
        /** on debug mode, allow bypass for token validation */
        if ($this->isUsingBypassCode($code)) {
            return true;
        }

        return $code === $this->phone_number_verification_code;
    }

    public function isVerified(): bool
    {
        return filled($this->email_verified_at) || filled($this->phone_number_verified_at);
    }

    public function isPhoneNumberVerified(): bool
    {
        return filled($this->phone_number_verified_at);
    }

    public function isBlocked(): bool
    {
        return filled($this->blocked_at);
    }

    public function hasEmail(): bool
    {
        return filled($this->email);
    }

    public function hasPhoneNumber(): bool
    {
        return filled($this->phone_number);
    }

    public function hasPassword(): bool
    {
        return filled($this->password);
    }

    public function onboard(): void
    {
        if ($this->isOnboarded()) {
            return;
        }

        $this->onboarded_at = now();
        $this->save();
    }

    public function isOnboarded(): bool
    {
        return filled($this->onboarded_at);
    }

    public function getDefaultRoleName(): string
    {
        if ($this->hasRole(Role::USER)) {
            return Role::USER;
        } else if ($this->hasRole(Role::GUEST)) {
            return Role::GUEST;
        } else if ($this->hasRole(Role::MERCHANT)) {
            return Role::MERCHANT;
        }

        return Role::USER;
    }

    public function defaultAvatar(): string
    {
        return asset('/images/default-profile.svg');
    }

    public function verifyEmailNow(): bool
    {
        return $this->update(['email_verified_at' => now()]);
    }

    public function verifyPhoneNumberNow(): bool
    {
        return $this->update(['phone_number_verified_at' => now()]);
    }

    public function isEmailPrimary(): bool
    {
        return $this->primary_username === UsernameType::EMAIL;
    }

    public function isPhonePrimary(): bool
    {
        return $this->primary_username === UsernameType::PHONE_NUMBER;
    }

    public function routeNotificationForSms($notification): ?string
    {
        return $this->phone_number ? $this->uncleanPhoneNumber($this->phone_number) : null;
    }

    public function createIDNumber()
    {
        $id = $this->generateNumber();

        $this->update(['member_id' => $id]);
    }

    public function generateNumber(): string
    {
        do {
            $generated = mt_rand(1000000000000000, 9999999999999999);
        } while (User::where('member_id', $generated)->first());

        return $generated;
    }

    /*
    |--------------------------------------------------------------------------
    | One-Time-Password
    |--------------------------------------------------------------------------
    */

    /**
     * Validates if the one time password is correct.
     *
     * If otp is a valid one time password, it will invalidate the old
     * one and return true.
     */
    public function invalidateIfValidOneTimePassword(string $value): bool
    {
        if ($this->isUsingBypassCode($value)) {
            return true;
        }

        if ($this->traitHasValidOneTimePassword($value)) {
            $this->invalidateOneTimePassword();
            return true;
        }

        return false;
    }

    public function otpChannel(): string
    {
        return SmsChannel::class;
    }

    public function otpDestination(): string
    {
        return $this->uncleanPhoneNumber($this->phone_number);
    }
}
