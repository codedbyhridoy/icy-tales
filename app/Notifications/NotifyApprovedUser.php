<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyApprovedUser extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public $user) {}

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $mail = (new MailMessage)
            ->subject('Welcome ' . $this->user->first_name . '! Got Approved.')
            ->greeting('Hello ' . $this->user->first_name . ',')
            ->line('Your account has been approved. You can login now.')
            ->line('If you did not expect this email, please ignore it.');

        return $mail;
    }
}
