<?php

return [
    'default' => env('SMS_CONNECTION', 'log'),
    'connections' => [
        'log' => [
            'driver' => Zing\LaravelSms\Drivers\LogDriver::class,
            'channel' => env('SMS_LOG_CHANNEL', null),
            'level' => env('SMS_LOG_LEVEL', 'info'),
        ],
        'null' => [
            'driver' => Zing\LaravelSms\Drivers\NullDriver::class,
        ],
        'yunpian' => [
            'driver' => Zing\LaravelSms\Drivers\YunpianDriver::class,
            'api_key' => env('SMS_YUNPIAN_API_KEY'),
        ],
        'meilian' => [
            'driver' => Zing\LaravelSms\Drivers\MeilianDriver::class,
            'username' => env('SMS_MEILIAN_USERNAME'),
            'password' => env('SMS_MEILIAN_PASSWORD'),
            'api_key' => env('SMS_MEILIAN_API_KEY'),
        ],
    ],
];
