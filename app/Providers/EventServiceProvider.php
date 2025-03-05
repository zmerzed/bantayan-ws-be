<?php

namespace App\Providers;

use App\Events\OnboardUser;
use App\Listeners\CreateUniqueIDNumber;
use App\Models\OrderStatusHistory;
use App\Observers\OrderObserver;
use App\Observers\OrderStatusHistoryObserver;
use Kolette\Marketplace\Models\Order;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        OnboardUser::class => [
            CreateUniqueIDNumber::class
        ]
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //OrderStatusHistory::observe(OrderStatusHistoryObserver::class);
        //Order::observe(OrderObserver::class);
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
