<?php

declare(strict_types=1);

namespace Zing\LaravelSms\Tests;

use Overtrue\EasySms\Contracts\MessageInterface;
use Zing\LaravelSms\SmsMessage;

class MessageTest extends TestCase
{
    public function testStaticCreate(): void
    {
        $message = SmsMessage::text('');
        self::assertSame(MessageInterface::TEXT_MESSAGE, $message->getMessageType());
        $message = SmsMessage::voice('');
        self::assertSame(MessageInterface::VOICE_MESSAGE, $message->getMessageType());
        $message = SmsMessage::fromTemplate('', []);
        self::assertSame(MessageInterface::TEXT_MESSAGE, $message->getMessageType());
    }
}
