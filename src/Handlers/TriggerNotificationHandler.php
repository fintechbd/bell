<?php

namespace Fintech\Bell\Handlers;

use Fintech\Bell\Notifications\DynamicNotification;
use Fintech\Core\Enums\Bell\NotificationMedium;
use Fintech\Core\Facades\Core;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Notification;

class TriggerNotificationHandler
{
    public function handle($event, array $variables): void
    {
        foreach ($event->templates() as $template) {
            Notification::send($this->recipients($event, $template), new DynamicNotification($template->medium->value, $template->content, $variables));
        }
    }

    private function systemAdmin(): mixed
    {
        if (Core::packageExists('Auth')) {
            return \Fintech\Auth\Facades\Auth::user()->findWhere(['id' => 1]);
        }

        return null;
    }

    private function eventUser($event): mixed
    {
        if (method_exists($event, 'user')) {
            return $event->user();
        }

        return null;
    }

    private function eventAgent($event): mixed
    {
        if (property_exists($event, 'agent')) {
            return $event->agent;
        }

        return null;
    }

    private function recipients(object $event, object $template): Collection
    {
        $templateRecipients = $template->recipients;

        $recipients = collect();

        if (isset($templateRecipients['admin']) && $templateRecipients['admin'] === true) {
            if ($admin = $this->systemAdmin()) {
                $recipients->push($admin);
            }
        }

        if (isset($templateRecipients['customer']) && $templateRecipients['customer'] === true) {
            if ($customer = $this->eventUser($event)) {
                $recipients->push($customer);
            }
        }

        if (isset($templateRecipients['agent']) && $templateRecipients['agent'] === true) {
            if ($agent = $this->eventAgent($event)) {
                $recipients->push($agent);
            }
        }

        foreach ($templateRecipients['extra'] as $recipient) {
            $recipient = trim($recipient);

            if ($template->medium == NotificationMedium::Sms) {
                if (preg_match('/^\+[1-9]\d{9,14}$/i', $recipient) === 1) {
                    $recipients->push(Notification::route(NotificationMedium::Sms->value, $recipient));
                }
            } elseif ($template->medium == NotificationMedium::Email) {
                if (filter_var($recipient, FILTER_VALIDATE_EMAIL) !== false) {
                    $recipients->push(Notification::route(NotificationMedium::Email->value, [$recipient => 'Anonymous Notification']));
                }
            } elseif ($template->medium == NotificationMedium::Chat) {
                if (filter_var($recipient, FILTER_VALIDATE_INT) !== false && Core::packageExists('Auth')) {
                    if ($user = \Fintech\Auth\Facades\Auth::user()->find($recipient)) {
                        $recipients->push($user);
                    }
                }
            } elseif ($template->medium == NotificationMedium::Push) {
                $recipients->push($recipient);
            }
        }

        return $recipients->filter(fn ($recipient) => gettype($recipient) == 'object');
    }
}
