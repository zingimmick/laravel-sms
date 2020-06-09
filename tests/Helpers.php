<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Log;
use Overtrue\EasySms\Message;

function prepareLoggerExpectation($channel = null, $level = 'info')
{
    Log::shouldReceive('channel')->once()->with($channel)->andReturn($logChannel = \Mockery::mock());
    Log::shouldReceive('debug')->withAnyArgs()->twice();

    return $logChannel->shouldReceive($level)->once();
}

function sendString($number, $message)
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

    return sprintf('number: %s, message: "%s", template: "%s", data: %s, type: %s', $number, $message->getContent(), $message->getTemplate(), json_encode($message->getData()), $message->getMessageType());
}
