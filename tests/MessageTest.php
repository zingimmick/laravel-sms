<?php

namespace Zing\LaravelSms\Tests;

use Overtrue\EasySms\Contracts\MessageInterface;
use Zing\LaravelSms\Message;

class MessageTest extends TestCase
{
    public function test_static_create()
    {
        $message = Message::text('');
        self::assertSame(MessageInterface::TEXT_MESSAGE, $message->getMessageType());
        $message = Message::voice('');
        self::assertSame(MessageInterface::VOICE_MESSAGE, $message->getMessageType());
        $message = Message::fromTemplate('', []);
        self::assertSame(MessageInterface::TEXT_MESSAGE, $message->getMessageType());
    }
}
