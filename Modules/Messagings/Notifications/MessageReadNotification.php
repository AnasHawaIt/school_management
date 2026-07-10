<?php

namespace Modules\Messagings\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MessageReadNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $message;
    protected $reader;

    /**
     * Create a new notification instance.
     */
    public function __construct($message, $reader)
    {
        $this->message=$message;
        $this->reader=$reader;
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
            'type' => 'read',
            'title' => 'Message Read',
            'message' => 'Your message has been read.',
            'message_id' => $this->message->id,
            'reader_id' => $this->reader->id,
            'created_at' => now(),
        ];
    }
}
