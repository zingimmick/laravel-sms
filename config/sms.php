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
            'driver' => Overtrue\EasySms\Gateways\AliyunGateway::class,
            'access_key_id' => env('SMS_ALIYUN_ACCESS_KEY_ID'),
            'access_key_secret' => env('SMS_ALIYUN_ACCESS_KEY_SECRET'),
            'sign_name' => env('SMS_ALIYUN_ACCESS_SIGN_NAME'),
        ],
        'aliyunrest' => [
            'driver' => Overtrue\EasySms\Gateways\AliyunrestGateway::class,
            'app_key' => env('SMS_ALIYUNREST_APP_KEY'),
            'app_secret_key' => env('SMS_ALIYUNREST_APP_SECRET_KEY'),
            'sign_name' => env('SMS_ALIYUNREST_SIGN_NAME'),
        ],
        'avatardata' => [
            'driver' => Overtrue\EasySms\Gateways\AvatardataGateway::class,
            'app_key' => env('SMS_AVATARDATA_APP_KEY'),
        ],
        'baidu' => [
            'driver' => Overtrue\EasySms\Gateways\BaiduGateway::class,
            'domain' => env('SMS_BAIDU_DOMAIN'),
            'ak' => env('SMS_BAIDU_AK'),
            'sk' => env('SMS_BAIDU_SK'),
            'invoke_id' => env('SMS_BAIDU_INVOKED_ID'),
        ],
        'chuanglan' => [
            'driver' => Overtrue\EasySms\Gateways\ChuanglanGateway::class,
            'channel' => env('SMS_CHUANGLAN_CHANNEL'),
            'account' => env('SMS_CHUANGLAN_ACCOUNT'),
            'password' => env('SMS_CHUANGLAN_PASSWORD'),
            'intel_account' => env('SMS_CHUANGLAN_INTEL_ACCOUNT'),
            'intel_password' => env('SMS_CHUANGLAN_INTEL_PASSWORD'),
            'sign' => env('SMS_CHUANGLAN_SIGN'),
            'unsubscribe' => env('SMS_CHUANGLAN_UNSUBSCRIBE'),
        ],
        'errorlog' => [
            'driver' => Overtrue\EasySms\Gateways\ErrorlogGateway::class,
            'file' => '/tmp/easy-sms.log',
        ],
        'huawei' => [
            'driver' => Overtrue\EasySms\Gateways\HuaweiGateway::class,
            'endpoint' => env('SMS_HUAWEI_ENDPOINT'),
            'app_key' => env('SMS_HUAWEI_APP_KEY'),
            'app_secret' => env('SMS_HUAWEI_APP_SECRET'),
            'from' => env('SMS_HUAWEI_FROM'),
            'callback' => env('SMS_HUAWEI_CALLBACK'),
        ],
        'huaxin' => [
            'driver' => Overtrue\EasySms\Gateways\HuaxinGateway::class,
            'ip' => env('SMS_HUAXIN_IP'),
            'user_id' => env('SMS_HUAXIN_USER_ID'),
            'account' => env('SMS_HUAXIN_ACCOUNT'),
            'password' => env('SMS_HUAXIN_PASSWORD'),
            'ext_no' => env('SMS_HUAXIN_EXT_NO'),
        ],
        'huyi' => [
            'driver' => Overtrue\EasySms\Gateways\HuyiGateway::class,
            'api_id' => env('SMS_HUYI_API_ID'),
            'api_key' => env('SMS_HUYI_API_KEY'),
            'signature' => env('SMS_HUYI_SIGNATURE'),
        ],
        'juhe' => [
            'driver' => Overtrue\EasySms\Gateways\JuheGateway::class,
            'app_key' => env('SMS_JUHE_APP_KEY'),
        ],
        'kingtto' => [
            'driver' => Overtrue\EasySms\Gateways\KingttoGateway::class,
            'userid' => env('SMS_KINGTTO_USERID'),
            'account' => env('SMS_KINGTTO_ACCOUNT'),
            'password' => env('SMS_KINGTTO_PASSWORD'),
        ],
        'luosimao' => [
            'driver' => Overtrue\EasySms\Gateways\LuosimaoGateway::class,
            'api_key' => env('SMS_LUOSIMAO_API_KEY'),
        ],
        'meilian' => [
            'driver' => Zing\LaravelSms\Gateways\MeilianGateway::class,
            'username' => env('SMS_MEILIAN_USERNAME'),
            'password' => env('SMS_MEILIAN_PASSWORD'),
            'api_key' => env('SMS_MEILIAN_API_KEY'),
        ],
        'qcloud' => [
            'driver' => Overtrue\EasySms\Gateways\QcloudGateway::class,
            'app_key' => env('SMS_QCOULD_APP_KEY'),
            'sdk_app_id' => env('SMS_QCOULD_SDK_APP_ID'),
            'sign_name' => env('SMS_QCOULD_SIGN_NAME'),
        ],
        'rongcloud' => [
            'driver' => \Overtrue\EasySms\Gateways\RongcloudGateway::class,
            'app_key' => env('SMS_RONGCLOUD_APP_KEY'),
            'app_secret' => env('SMS_RONGCLOUD_APP_SECRET'),
        ],
        'sendcloud' => [
            'driver' => \Overtrue\EasySms\Gateways\SendcloudGateway::class,
            'sms_user' => env('SMS_SENDCLOUD_SMS_USER'),
            'sms_key' => env('SMS_SENDCLOUD_SMS_KEY'),
            'timestamp' => env('SMS_SENDCLOUD_TIMESTAMP'),
        ],
    ],
];
