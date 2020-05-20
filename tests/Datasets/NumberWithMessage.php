<?php

declare(strict_types=1);

use Zing\LaravelSms\PhoneNumber;
use Zing\LaravelSms\SmsMessage;

dataset(
    'numbers',
    [
        ['18888888888', 'This is a test message.'],
        [new PhoneNumber('18888888888', '+86'), SmsMessage::text('This is a test message.')],
    ]
);
