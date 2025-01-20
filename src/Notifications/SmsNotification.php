<?php

namespace Fintech\Bell\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Laraflow\Sms\Exceptions\DriverNotFoundException;
use Laraflow\Sms\SmsMessage;

class SmsNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public string $message;

    /**
     * Create a new notification instance.
     */
    public function __construct(public object $template, public array $replacements = [])
    {

    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['sms'];
    }

    /**
     * Get the SMS representation of the notification.
     */
    /**
     * @throws DriverNotFoundException
     * @throws \ErrorException
     */
    public function toSms(object $notifiable): SmsMessage
    {
        return (new SmsMessage)
            ->from(decide_sms_from_name($this->ro))
            ->message(strtr($this->template->content['message'], $this->replacements));
    }

    private function recipients()
    {
        $recipients = $this->template->recipients;

        if (empty($recipients)) {
            throw new \ErrorException("Recipients is empty");
        }
    }
}
