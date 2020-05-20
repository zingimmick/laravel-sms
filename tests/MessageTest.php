<?php

declare(strict_types=1);

namespace Zing\LaravelSms\Tests;

use Overtrue\EasySms\Contracts\MessageInterface;
use Zing\LaravelSms\SmsMessage;

test('create message with static call', function (): void {
    $message = SmsMessage::text('');
    assertSame(MessageInterface::TEXT_MESSAGE, $message->getMessageType());
    $message = SmsMessage::voice('');
    assertSame(MessageInterface::VOICE_MESSAGE, $message->getMessageType());
    $message = SmsMessage::fromTemplate('', []);
    assertSame(MessageInterface::TEXT_MESSAGE, $message->getMessageType());
});
