<?php

namespace Fintech\Bell\Notifications;

use Fintech\Bell\Messages\PushMessage;
use Fintech\Core\Enums\Bell\NotificationMedium;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Laraflow\Sms\SmsMessage;

class DynamicNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Determine if the notification should be sent.
     */
    public function shouldSend(object $notifiable, string $channel): bool
    {
        if ($notifiable instanceof AnonymousNotifiable) {

            $channelValue = $notifiable->routeNotificationFor($channel, $this);

            if (empty($channelValue)) {
                return false;
            }

            if ($channel == NotificationMedium::Email->value && filter_var($channelValue, FILTER_VALIDATE_EMAIL) === false) {
                return false;
            }

            return true;
        }

        return $notifiable->routeNotificationFor($channel, $this) != null;
    }

    public function __construct(public string $channel, public array $content, public array $replacements = []) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(?object $notifiable = null): array
    {
        return [$this->channel];
    }

    /**
     * Get the mail representation of the notification.
     */
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

    /**
     * Get the sms representation of the notification.
     */
    public function toSms(?object $notifiable = null): SmsMessage
    {
        return (new SmsMessage)
            ->from(decide_sms_from_name($notifiable->routeNotificationFor($this->channel, $this)))
            ->message(strtr($this->content['body'] ?? 'SMS Body', $this->replacements));
    }

    /**
     * Get the push representation of the notification.
     */
    public function toPush(?object $notifiable = null): PushMessage
    {
        return (new PushMessage)
            ->type('general')
            ->title(strtr($this->content['title'] ?? 'Push Title', $this->replacements))
            ->body(strtr($this->content['body'] ?? 'Push body', $this->replacements));
    }

    /**
     * Get the in-app representation of the notification.
     */
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
