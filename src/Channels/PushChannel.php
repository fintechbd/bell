<?php

namespace Fintech\Bell\Channels;

use BadMethodCallException;
use Fintech\Bell\Facades\Bell;
use Fintech\Bell\Messages\PushMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

class PushChannel
{
    /**
     * @var \Fintech\Bell\Abstracts\PushDriver
     */
    private $driver;

    public function __construct()
    {
        $this->driver = Bell::push();
    }

    /**
     * Send the given notification.
     */
    public function send(object $notifiable, Notification $notification): void
    {
        if (! method_exists($notification, 'toPush')) {
            throw new BadMethodCallException(get_class($notification)." notification is missing the toPush(?object $notifiable = null): PushMessage method.");
        }

        try {
            /**
             * @var PushMessage $pushMessage
             */
            $pushMessage = $notification->toPush($notifiable);

            if (! $to = $notifiable->routeNotificationFor('push', $notification)) {
                throw new BadMethodCallException(get_class($notifiable)." notifiable is missing the `routeNotificationForPush(object $notifiable): string` method.");
            }

            $pushMessage->token($to);

            $this->driver->validate($pushMessage);

            $this->response = $this->driver->send($pushMessage);

        } catch (Exception $exception) {
            (App::isProduction())
                ? Log::error($exception)
                : throw new Exception($exception->getMessage(), 0, $exception);
        }
    }
}
