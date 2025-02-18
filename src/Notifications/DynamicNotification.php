<?php

namespace Fintech\Bell\Notifications;

use Fintech\Bell\Messages\PushMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Laraflow\Sms\SmsMessage;

class DynamicNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public string $channel, public array $content, public array $replacements = []) {}

    public function via(?object $notifiable = null): array
    {
        return [$this->channel];
    }

    public function toMail(?object $notifiable = null): MailMessage
    {
        return (new MailMessage)
            ->subject(strtr($this->content['title'] ?? 'Email Subject', $this->replacements))
            ->view('bell::email', [
                'phone' => '+8801689553434',
                'email' => config('mail.from.address'),
                'website' => 'www.lebupay.com',
                'content' => strtr($this->content['body'] ?? 'Email body', $this->replacements),
            ])
            ->priority(2);
    }

    public function toSms(?object $notifiable = null): SmsMessage
    {
        return (new SmsMessage)
            ->from(decide_sms_from_name($notifiable->routeNotificationFor($this->channel)))
            ->message(strtr($this->content['body'] ?? 'SMS Body', $this->replacements));
    }

    public function toPush(?object $notifiable = null): PushMessage
    {
        return (new PushMessage)
            ->type('general')
            ->title(strtr($this->content['title'] ?? 'Push Title', $this->replacements))
            ->body(strtr($this->content['body'] ?? 'Push body', $this->replacements));
    }

    public function toDatabase(?object $notifiable = null): array
    {
        return [
            'type' => $this->content['type'] ?? 'info',
            'title' => strtr($this->content['title'] ?? 'Notification Title', $this->replacements),
            'body' => strtr($this->content['body'] ?? 'Notification body', $this->replacements),
            'timestamp' => now(),
        ];
    }
}
