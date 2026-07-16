<?php

namespace Modules\Messagings\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewMessageNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $message;

    /**
     * Create a new notification instance.
     */
    public function __construct($message)
    {
        $this->$message=$message;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'new_message',
            'title' => 'New Message',
            'message' => 'You have received a new message.',
            'message_id' => $this->message->id,
            'sender_id' => $this->message->sender_id,
            'created_at' => now(),
        ];
    }

}
