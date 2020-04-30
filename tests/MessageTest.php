<?php

declare(strict_types=1);

namespace Zing\LaravelSms\Tests;

use Overtrue\EasySms\Contracts\MessageInterface;
use Zing\LaravelSms\Message;

class MessageTest extends TestCase
{
    public function testStaticCreate(): void
    {
        $message = Message::text('');
        self::assertSame(MessageInterface::TEXT_MESSAGE, $message->getMessageType());
        $message = Message::voice('');
        self::assertSame(MessageInterface::VOICE_MESSAGE, $message->getMessageType());
        $message = Message::fromTemplate('', []);
        self::assertSame(MessageInterface::TEXT_MESSAGE, $message->getMessageType());
    }
}
