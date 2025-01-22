<?php

namespace Fintech\Bell\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Laraflow\Sms\SmsMessage;

class DynamicNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public string $channel, public array $content, public array $replacements = []) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return (array) ($this->channel ?? 'database');
    }

    /**
     * Get the Mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
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

    /**
     * Get the SMS representation of the notification.
     */
    public function toSms(object $notifiable): SmsMessage
    {
        return (new SmsMessage)
            ->from(decide_sms_from_name($notifiable->routeNotificationFor($this->channel)))
            ->message(strtr($this->content['body'], $this->replacements));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toPush(object $notifiable): array
    {
        return [
            //
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
