<?php

namespace Zing\LaravelSms\Channels;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Notifications\Notification;
use Zing\LaravelSms\Message;
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
        $message = $notification->toSms($notifiable);
        $receiver = $notifiable->routeNotificationFor('sms', $notification);
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
}
