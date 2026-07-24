<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserRegistrationPendingNotification extends Notification
{
    use Queueable;

    protected $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function via($notifiable): array
    {
        return ['mail']; // আমরা শুধু ইমেইলের মাধ্যমে পাঠাবো
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Registration Successful - Account Pending Approval')
            ->greeting('Hello ' . $this->user->name . ',')
            ->line('Thank you for registering on our platform.')
            ->line('Your account has been created successfully, but it is currently **pending admin approval**.')
            ->line('You will receive another notification email once the Administrator approves your account or confirms your registration soon.')
            ->line('Thank you for your patience!');
    }
}
