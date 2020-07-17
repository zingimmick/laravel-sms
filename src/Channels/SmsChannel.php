<?php

declare(strict_types=1);

namespace Zing\LaravelSms\Channels;

use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Notifications\Notification;
use RuntimeException;
use Zing\LaravelSms\SmsManager;
use Zing\LaravelSms\SmsMessage;

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
    public function send($notifiable, Notification $notification): void
    {
        $message = $this->getData($notifiable, $notification);
        $receiver = $this->resolveReceiver($notifiable, $notification);
        if (!$receiver) {
            return;
        }

        if (is_string($message)) {
            $message = new SmsMessage(
                [
                    'content' => $message,
                    'template' => $message,
                ]
            );
        }

        if (!$message instanceof SmsMessage) {
            return;
        }

        $this->smsManager->connection($message->connection)->send($receiver, $message);
    }

    /**
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     *
     * @return mixed|null
     */
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

        throw new RuntimeException('Notification is missing toSms method.');
    }
}
