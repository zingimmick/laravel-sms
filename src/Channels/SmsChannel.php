<?php

namespace Zing\LaravelSms\Channels;

use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Notifications\Notification;
use Zing\LaravelSms\Message;
use Zing\LaravelSms\SmsManager;

/**
 * Class SmsChannel.
 */
class SmsChannel
{
    protected $smsManager;

    /**
     * Create a new database channel.
     *
     * @param \Zing\LaravelSms\SmsManager $smsManager
     */
    public function __construct(SmsManager $smsManager)
    {
        $this->smsManager = $smsManager;
    }

    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     *
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        $message = $this->getData($notifiable, $notification);
        $receiver = $this->resolveReceiver($notifiable, $notification);
        if (! $receiver) {
            return;
        }
        if (is_string($message)) {
            $message = Message::text($message);
        }
        if (! $message instanceof Message) {
            return;
        }

        return $this->smsManager->connection($message->connection)->send($receiver, $message);
    }

    public function resolveReceiver($notifiable, Notification $notification)
    {
        if ($notifiable instanceof AnonymousNotifiable) {
            $receiver = $notifiable->routeNotificationFor(static::class);
            if ($receiver) {
                return $receiver;
            }

            return $notifiable->routeNotificationFor('sms');
        }

        return $notifiable->routeNotificationFor('sms', $notification);
    }

    /**
     * Get the data for the notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     *
     * @return mixed
     *
     * @throws \RuntimeException
     */
    protected function getData($notifiable, Notification $notification)
    {
        if (method_exists($notification, 'toSms')) {
            return $notification->toSms($notifiable);
        }

        throw new \RuntimeException('Notification is missing toSms method.');
    }
}
