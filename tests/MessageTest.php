<?php

declare(strict_types=1);

namespace Zing\LaravelSms\Tests;

use Overtrue\EasySms\Contracts\MessageInterface;
use Zing\LaravelSms\SmsMessage;

/**
 * @internal
 */
final class MessageTest extends TestCase
{
    public function testStaticCreate(): void
    {
        $message = SmsMessage::text('');
        $this->assertSame(MessageInterface::TEXT_MESSAGE, $message->getMessageType());
        $message = SmsMessage::voice('');
        $this->assertSame(MessageInterface::VOICE_MESSAGE, $message->getMessageType());
        $message = SmsMessage::fromTemplate('', []);
        $this->assertSame(MessageInterface::TEXT_MESSAGE, $message->getMessageType());
    }
}
