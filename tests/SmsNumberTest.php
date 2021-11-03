<?php

declare(strict_types=1);

namespace Zing\LaravelSms\Tests;

use Illuminate\Support\Facades\Log;
use Mockery;
use Overtrue\EasySms\Message;
use Zing\LaravelSms\SmsNumber;

class SmsNumberTest extends TestCase
{
    /**
     * @param \Overtrue\EasySms\Contracts\PhoneNumberInterface|string $number
     * @param \Overtrue\EasySms\Contracts\MessageInterface|array<string, string>|string $message
     */
    protected function sendString($number, $message): string
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

        return sprintf(
            'number: %s, message: "%s", template: "%s", data: %s, type: %s',
            $number,
            $message->getContent(),
            $message->getTemplate(),
            json_encode($message->getData()),
            $message->getMessageType()
        );
    }

    public function testNotify(): void
    {
        $smsNumber = new SmsNumber('18188888888');
        $verifyCode = new VerifyCode();
        $this->prepareLoggerExpectation()
            ->with($this->sendString($smsNumber->routeNotificationForSms($verifyCode), $verifyCode->toSms($smsNumber)));
        $smsNumber->notify($verifyCode);
    }

    /**
     * @param string|null $channel
     * @phpstan-return \Mockery\Expectation
     */
    protected function prepareLoggerExpectation($channel = null, string $level = 'info')
    {
        Log::shouldReceive('channel')->once()->with($channel)->andReturn($logChannel = Mockery::mock());
        Log::shouldReceive('debug')->withAnyArgs()->twice();

        return $logChannel->shouldReceive($level)
            ->once();
    }
}
