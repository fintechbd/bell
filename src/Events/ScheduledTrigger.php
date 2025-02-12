<?php

namespace Fintech\Bell\Events;

use Fintech\Core\Abstracts\BaseEvent;
use Fintech\Core\Attributes\ListenByTrigger;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

#[ListenByTrigger(
    name: 'Scheduled Notification',
    description: 'Send scheduled notification to particular group or user',
    enabled: true,
    variables: []
)]
class ScheduledTrigger extends BaseEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct()
    {
        $this->init();
    }

    /**
     * List all the aliases that this event will provide
     * @return array
     */
    public function aliases(): array
    {
        return [];
    }
}
