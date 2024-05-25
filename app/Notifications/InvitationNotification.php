<?php

namespace App\Notifications;

use App\Models\Project;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InvitationNotification extends Notification
{
    public function __construct(
        public Project $project
    )
    {
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->line("You have been invited to {$this->project->name}!")
            ->line("Click the button below to accept the invitation.")
            ->action('Accept', url("/projects/{$this->project->id}/accept-invitation"));
    }

    public function toArray($notifiable): array
    {
        return [];
    }
}
