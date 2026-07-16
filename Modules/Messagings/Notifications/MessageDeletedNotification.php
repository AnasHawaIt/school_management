<?php

namespace Modules\Messagings\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MessageDeletedNotification extends Notification implements ShouldQueue
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
            'type'        => 'message_deleted',
            'title'       => 'Message Deleted',
            'message'     => 'A message has been deleted.',
            'message_id'  => $this->message->id,
            'deleted_by'  => auth()->id(),
            'icon'        => 'trash',
            'created_at'  => now(),
        ];
    }

}
