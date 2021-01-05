<?php

declare(strict_types=1);

use Zing\LaravelSms\SmsMessage;
use Zing\LaravelSms\SmsNumber;

dataset(
    'numbers',
    [
        ['18888888888', 'This is a test message.'],
        [new SmsNumber('18888888888', '+86'), SmsMessage::text('This is a test message.')],
    ]
);
