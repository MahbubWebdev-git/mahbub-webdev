<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdminNewUserAlertNotification extends Notification
{
    use Queueable;

    public $newUser;

    /**
     * Create a new notification instance.
     *
     */
    public function __construct($newUser)
    {
        $this->newUser = $newUser;
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
        $loginUrl = url('/login');
        
        $pendingUrl = route('admin.pending.users');

         return (new MailMessage)
            ->subject('Alert: New User Registration Pending Approval')
            ->greeting('Hello Admin,')
            ->line('A new user has just registered on your website and is waiting for your review.')
            ->line('**User Details:**')
            ->line('Name: ' . $this->newUser->name)
            ->line('Email: ' . $this->newUser->email)
            ->action('Review and Approve User', $pendingUrl) // এখানে $pendingUrl ধরিয়ে দেওয়া হলো
            ->line('Please login to the admin panel to approve this account.');
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
