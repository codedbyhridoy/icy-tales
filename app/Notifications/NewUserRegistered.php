<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewUserRegistered extends Notification implements ShouldQueue
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
            ->subject('New user registered')
            ->line('A new user has registered.')
            ->line('Name: ' . ($this->user->first_name . ' ' . $this->user->last_name)??'')
            ->line('Email: ' . ($this->user->email ?? '-'));

        // Role-based customization
        if ($this->user->hasRole('practice')) {
            $mail->line('Role: Practice')
                ->line('Please review the practice profile and approve if needed.');
        }

        if ($this->user->hasRole('specialist')) {
            $mail->line('Role: Specialist')
                ->line('Please review specialist credentials and certificates.');
        }

        if ($this->user->hasRole('admin')) {
            $mail->line('Role: Admin');
        }

        return $mail;
    }
}
