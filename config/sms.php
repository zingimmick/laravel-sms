<?php

use Zing\LaravelSms\Drivers\LogDriver;
use Zing\LaravelSms\Drivers\NullDriver;
use Zing\LaravelSms\Drivers\YunpianDriver;

return [
    'default' => env('SMS_CONNECTION', 'log'),
    'connections' => [
        'log' => [
            'driver' => LogDriver::class,
            'channel' => env('SMS_LOG_CHANNEL', null),
            'level' => env('SMS_LOG_LEVEL', 'info'),
        ],
        'null' => [
            'driver' => NullDriver::class,
        ],
        'yunpian' => [
            'driver' => YunpianDriver::class,
            'api_key' => env('YUNPIAN_KEY'),
        ],
    ],
];
