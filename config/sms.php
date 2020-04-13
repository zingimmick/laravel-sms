<?php

use Zing\LaravelSms\Drivers\LogDriver;
use Zing\LaravelSms\Drivers\MeilianDriver;
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
            'api_key' => env('SMS_YUNPIAN_API_KEY'),
        ],
        'meilian' => [
            'driver' => MeilianDriver::class,
            'username' => env('SMS_MEILIAN_USERNAME'),
            'password' => env('SMS_MEILIAN_PASSWORD'),
            'api_key' => env('SMS_MEILIAN_API_KEY'),
        ],
    ],
];
