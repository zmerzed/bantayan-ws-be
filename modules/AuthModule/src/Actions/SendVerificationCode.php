<?php

namespace Kolette\Auth\Actions;

use Kolette\Auth\Models\User;
use Kolette\Auth\Notifications\VerifyEmail;
use Kolette\Auth\Notifications\VerifyPhoneNumber;

class SendVerificationCode
{
    public User $user;

    public function execute(User $user, string $via = null): void
    {
        $this->user = $user;

        if ($via == 'email') {
            $this->sendCodeViaEmail();
        } elseif ($via == 'phone_number') {
            $this->sendCodeViaPhoneNumber();
        } else {
            if (!empty($this->user->email)) {
                $this->sendCodeViaEmail();
            } elseif (!empty($this->user->phone_number)) {
                $this->sendCodeViaPhoneNumber();
            }
        }
    }

    private function sendCodeViaEmail(): void
    {
        if (!$this->user->isEmailVerified()) {
            $this->user->notify(new VerifyEmail());
        }
    }

    private function sendCodeViaPhoneNumber(): void
    {
        if (!$this->user->isPhoneNumberVerified()) {
            $this->user->notify(new VerifyPhoneNumber());
        }
    }
}
