<?php

return [
    'default' => env('SMS_CONNECTION', 'log'),
    'connections' => [
        'log' => [
            'driver' => \Zing\LaravelSms\Drivers\LogDriver::class,
        ],
        'null' => [
            'driver' => \Zing\LaravelSms\Drivers\NullDriver::class,
        ],
        'yunpian' => [
            'driver' => \Zing\LaravelSms\Drivers\YunPianDriver::class,
            'api_key' => env('YUNPIAN_KEY'),
        ],
    ],
];
