<?php

namespace Modules\Messagings\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MessageForwardedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $message;

    /**
     * Create a new notification instance.
     */
    public function __construct($message)
    {
        $this->message=$message;
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
            'type' => 'forward',
            'title' => 'Message Forwarded',
            'message' => 'A message has been forwarded to you.',
            'message_id' => $this->message->id,
            'sender_id' => $this->message->sender_id,
            'created_at' => now(),
        ];
    }
}
