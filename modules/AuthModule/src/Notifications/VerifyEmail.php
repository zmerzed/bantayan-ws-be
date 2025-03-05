<?php

namespace Kolette\Auth\Notifications;

use Kolette\Auth\Mail\VerifyEmail as SendVerifyEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class VerifyEmail extends Notification implements ShouldQueue
{
    use Queueable;

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): SendVerifyEmail
    {
        return (new SendVerifyEmail($notifiable))->to($notifiable->email);
    }
}
