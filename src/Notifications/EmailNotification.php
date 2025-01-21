<?php

namespace Fintech\Bell\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EmailNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public object $template, public array $replacements = [])
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(strtr($this->template->content['subject'] ?? 'Email Subject', $this->replacements))
            ->view('bell::email', [
                'phone' => '+8801689553434',
                'email' => config('mail.from.address'),
                'website' => 'www.lebupay.com',
                'content' => strtr($this->template->content['body'] ?? 'Email body', $this->replacements)
            ])
            ->priority(2);
    }
}
