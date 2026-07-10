<?php

namespace Modules\Messagings\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MessageRestoredNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $message;

    public function __construct($message)
    {
        $this->message=$message;
    }

    public function via($notifiable)
    {
        return ['database','mail']; // أو mail
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */

    /**
     * Get the mail representation of the notification.
     */
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
            'type'         => 'message_restored',
            'title'        => 'Message Restored',
            'message'      => 'A deleted message has been restored.',
            'message_id'   => $this->message->id,
            'restored_by'  => auth()->id(),
            'icon'         => 'restore',
            'created_at'   => now(),
        ];
    }
}
