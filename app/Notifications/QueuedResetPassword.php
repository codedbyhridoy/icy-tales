<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Auth\Notifications\ResetPassword;

class QueuedResetPassword extends ResetPassword implements ShouldQueue
{
    use Queueable;

    public function toMail($notifiable)
    {
        $url = $this->resetUrl($notifiable);


        if($notifiable->email_verified_at){
            $msg = 'Please reset password from the link. Click the button Or visit from link.';
            $btn_txt = 'Reset Password';
        }else{
            $msg = 'Your account has been created. Click the button below to activate it.';
            $btn_txt = 'Verify and Reset Password';
        }

        return (new MailMessage)
            ->subject('Welcome ' . $notifiable->first_name . '! Set Your Password')
            ->greeting('Hello ' . $notifiable->first_name . ',')
            ->line($msg)
            ->action($btn_txt, $url)
            ->line('If you did not expect this email, please ignore it.');
    }
}
