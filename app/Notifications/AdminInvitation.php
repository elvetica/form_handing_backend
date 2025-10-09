<?php

namespace App\Notifications;

use App\Models\AdminInvitation as AdminInvitationModel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdminInvitation extends Notification
{
    use Queueable;

    protected $invitation;

    /**
     * Create a new notification instance.
     */
    public function __construct(AdminInvitationModel $invitation)
    {
        $this->invitation = $invitation;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        $url = route('admin.register', ['token' => $this->invitation->token]);

        return (new MailMessage)
            ->subject('You\'re invited to join as an Admin')
            ->greeting('Hello!')
            ->line('You have been invited to join as an administrator.')
            ->line('Click the button below to create your admin account.')
            ->action('Accept Invitation', $url)
            ->line('This invitation will expire on ' . $this->invitation->expires_at->format('M d, Y g:i A'))
            ->line('If you did not expect this invitation, please ignore this email.');
    }
}
