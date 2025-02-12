<?php

namespace Fintech\Bell\Providers;

use Fintech\Bell\Events\ScheduledTrigger;
use Fintech\Core\Listeners\TriggerListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        ScheduledTrigger::class => [
            TriggerListener::class,
        ],
    ];
}
