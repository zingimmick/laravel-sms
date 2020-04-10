<?php

namespace Zing\LaravelSms\Channels;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Notifications\Notification;
use RuntimeException;
use Zing\LaravelSms\Messages\SmsMessage;
use Zing\LaravelSms\SmsManager;

/**
 * Class SmsChannel.
 */
class SmsChannel
{
    /**
     * The event dispatcher.
     *
     * @var \Illuminate\Contracts\Events\Dispatcher
     */
    protected $events;

    protected $smsManager;

    /**
     * Create a new database channel.
     *
     * @param \Illuminate\Contracts\Events\Dispatcher $events
     * @param \Zing\LaravelSms\SmsManager $smsManager
     */
    public function __construct(Dispatcher $events, SmsManager $smsManager)
    {
        $this->events = $events;
        $this->smsManager = $smsManager;
    }

    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     *
     * @return array|null
     */
    public function send($notifiable, Notification $notification)
    {
        $message = $this->getData($notifiable, $notification);
        $receiver = $notifiable->routeNotificationFor('sms', $notification);
        if (! $receiver) {
            return;
        }
        if ($message instanceof SmsMessage) {
            return $this->smsManager->connection($message->connection)->send($receiver, $message);
        }

        return $this->smsManager->send($receiver, $message);
    }

    /**
     * Get the data for the notification.
     *
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     *
     * @throws \RuntimeException
     *
     * @return mixed
     */
    protected function getData($notifiable, Notification $notification)
    {
        if (method_exists($notification, 'toSms')) {
            return $notification->toSms($notifiable);
        }

        if (method_exists($notification, 'toArray')) {
            return $notification->toArray($notifiable);
        }

        throw new RuntimeException('Notification is missing toArray method.');
    }
}
