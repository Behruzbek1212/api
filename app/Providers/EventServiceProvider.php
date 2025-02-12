<?php

namespace App\Providers;

use App\Events\Registered;
use App\Events\SendMessage;
use App\Events\TelegramSendNotification;
use App\Listeners\SendMessageListener;
use App\Listeners\SendPhoneVerification;
use App\Listeners\TelegramSendNotificationListener;
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
            SendPhoneVerification::class,
        ],
        TelegramSendNotification::class => [
            TelegramSendNotificationListener::class,
        ],
        SendMessage::class => [
            SendMessageListener::class,
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
