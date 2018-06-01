<?php

namespace p4scu41\BaseCRUDApi\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

/**
 * Notifications Base Class
 *
 * @category Notifications
 * @package  p4scu41\BaseCRUDApi\Notifications
 * @author   Pascual Pérez <pasperezn@gmail.com>
 * @created  2018-04-04
 */
class BaseNotification extends Notification
{
    use Queueable;

    /**
     * Data sends to Listener
     *
     * @var mixed
     */
    public $data;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($data = null)
    {
        $this->data = $data;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable Channel
     *
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable Channel
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable Channel
     *
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
