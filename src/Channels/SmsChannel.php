<?php

declare(strict_types=1);

namespace Zing\LaravelSms\Channels;

use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Notifications\Notification;
use Zing\LaravelSms\SmsManager;
use Zing\LaravelSms\SmsMessage;

class SmsChannel
{
    /**
     * Create a new database channel.
     */
    public function __construct(
        protected SmsManager $smsManager
    ) {
    }

    /**
     * Send the given notification.
     */
    public function send(mixed $notifiable, Notification $notification): void
    {
        $message = $this->getData($notifiable, $notification);
        $receiver = $this->resolveReceiver($notifiable, $notification);
        if (! $receiver) {
            return;
        }

        if (\is_string($message)) {
            $message = new SmsMessage(
                [
                    'content' => $message,
                    'template' => $message,
                ]
            );
        }

        if (! $message instanceof SmsMessage) {
            return;
        }

        $this->smsManager->connection($message->connection)
            ->send($receiver, $message);
    }

    public function resolveReceiver(mixed $notifiable, Notification $notification): mixed
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
     */
    protected function getData(mixed $notifiable, Notification $notification): mixed
    {
        if (method_exists($notification, 'toSms')) {
            return $notification->toSms($notifiable);
        }

        throw new \RuntimeException('Notification is missing toSms method.');
    }
}
