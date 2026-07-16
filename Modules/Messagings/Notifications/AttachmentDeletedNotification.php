<?php

namespace Modules\Messagings\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AttachmentDeletedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $message;
    protected $attachment;

    /**
     * Create a new notification instance.
     */
    public function __construct($Message, $attachment)
    {
        $this->message=$Message;
        $this->attachment=$attachment;
    }


    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'attachment_deleted',
            'title' => 'Attachment Deleted',
            'message' => 'An attachment has been deleted.',
            'message_id' => $this->message->id,
            'attachment_id' => $this->attachment->id,
            'created_at' => now(),
        ];
    }
}
