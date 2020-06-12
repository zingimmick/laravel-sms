<?php

declare(strict_types=1);

return [
    'default' => env('SMS_CONNECTION', 'log'),
    'connections' => [
        'log' => [
            'driver' => Zing\LaravelSms\Gateways\LogGateway::class,
            'channel' => env('SMS_LOG_CHANNEL', null),
            'level' => env('SMS_LOG_LEVEL', 'info'),
        ],
        'null' => [
            'driver' => Zing\LaravelSms\Gateways\NullGateway::class,
        ],
        'yunpian' => [
            'driver' => Overtrue\EasySms\Gateways\YunpianGateway::class,
            'api_key' => env('SMS_YUNPIAN_API_KEY'),
            'signature' => env('SMS_YUNPIAN_SIGNATURE'),
        ],
        'yunpian-market' => [
            'driver' => Overtrue\EasySms\Gateways\YunpianGateway::class,
            'api_key' => env('SMS_YUNPIAN_MARKET_API_KEY'),
            'signature' => env('SMS_YUNPIAN_MARKET_SIGNATURE'),
        ],
        'aliyun' => [
            'driver' => \Overtrue\EasySms\Gateways\AliyunGateway::class,
            'access_key_id' => env('SMS_ALIYUN_ACCESS_KEY_ID'),
            'access_key_secret' => env('SMS_ALIYUN_ACCESS_KEY_SECRET'),
            'sign_name' => env('SMS_ALIYUN_ACCESS_SIGN_NAME'),
        ],
        'aliyunrest' => [
            'driver' => \Overtrue\EasySms\Gateways\AliyunrestGateway::class,
            'app_key' => env('SMS_ALIYUNREST_APP_KEY'),
            'app_secret_key' => env('SMS_ALIYUNREST_APP_SECRET_KEY'),
            'sign_name' => env('SMS_ALIYUNREST_SIGN_NAME'),
        ],
        'avatardata' => [
            'driver' => \Overtrue\EasySms\Gateways\AvatardataGateway::class,
            'app_key' => env('SMS_AVATARDATA_APP_KEY'),
        ],
        'meilian' => [
            'driver' => Zing\LaravelSms\Gateways\MeilianGateway::class,
            'username' => env('SMS_MEILIAN_USERNAME'),
            'password' => env('SMS_MEILIAN_PASSWORD'),
            'api_key' => env('SMS_MEILIAN_API_KEY'),
        ],
    ],
];
