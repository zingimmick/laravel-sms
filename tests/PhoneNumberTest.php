<?php

declare(strict_types=1);

namespace Zing\LaravelSms\Tests;

use Illuminate\Support\Facades\Log;
use Mockery;
use Overtrue\EasySms\Message;
use Zing\LaravelSms\PhoneNumber;

class PhoneNumberTest extends TestCase
{
    protected function sendString($number, $message)
    {
        if (is_string($message)) {
            $message = new Message(
                [
                    'content' => $message,
                    'template' => $message,
                ]
            );
        }

        if (is_array($message)) {
            $message = new Message($message);
        }

        return sprintf('number: %s, message: "%s", template: "%s", data: %s, type: %s', $number, $message->getContent(), $message->getTemplate(), json_encode($message->getData(), JSON_THROW_ON_ERROR), $message->getMessageType());
    }

    public function testNotify(): void
    {
        $phone = new PhoneNumber('18188888888');
        $notification = new VerifyCode();
        $this->prepareLoggerExpectation()->with($this->sendString($phone->routeNotificationForSms($notification), $notification->toSms($phone)));
        $phone->notify($notification);
    }

    protected function prepareLoggerExpectation($channel = null, $level = 'info')
    {
        Log::shouldReceive('channel')->once()->with($channel)->andReturn($logChannel = Mockery::mock());
        Log::shouldReceive('debug')->withAnyArgs()->twice();

        return $logChannel->shouldReceive($level)->once();
    }
}
