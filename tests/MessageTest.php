<?php

namespace Zing\LaravelSms\Tests;

use Zing\LaravelSms\Message;

class MessageTest extends TestCase
{
    public function test_static_create()
    {
        $message = Message::text('');
        self::assertSame(\Zing\LaravelSms\Contracts\Message::TEXT, $message->getMessageType());
        $message = Message::voice('');
        self::assertSame(\Zing\LaravelSms\Contracts\Message::VOICE, $message->getMessageType());
        $message = Message::fromTemplate('', []);
        self::assertSame(\Zing\LaravelSms\Contracts\Message::TEXT, $message->getMessageType());
    }
}
