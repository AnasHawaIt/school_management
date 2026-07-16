<?php

namespace Modules\Messagings\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MessageFailedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $messageModel;
    protected $reason;

    public function __construct($messageModel, string $reason)
    {
        $this->messageModel = $messageModel;
        $this->reason = $reason;
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
            'type'        => 'message_failed',
            'title'       => 'Message Delivery Failed',
            'message'     => 'The message could not be delivered.',
            'message_id'  => $this->messageModel->id,
            'reason'      => $this->reason,
            'icon'        => 'error',
            'created_at'  => now(),
        ];
    }
}
