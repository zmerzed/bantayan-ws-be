<?php

namespace App\Listeners;

use App\Events\OnboardUser;
use Kolette\Auth\Enums\Role;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateUniqueIDNumber
{
    /**
     * Handle the event.
     */
    public function handle(OnboardUser $event): void
    {
        if ($event->user->hasRole(Role::USER) || $event->user->hasRole(Role::CUSTOMER)) {
            $event->user->createIDNumber();
        }
    }
}
