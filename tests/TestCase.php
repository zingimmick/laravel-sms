<?php

namespace Zing\LaravelSms\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use Zing\LaravelSms\Drivers\LogDriver;
use Zing\LaravelSms\Drivers\NullDriver;
use Zing\LaravelSms\Drivers\YunPianDriver;
use Zing\LaravelSms\Facades\Sms;
use Zing\LaravelSms\SmsServiceProvider;

class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            SmsServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app)
    {
        return ['sms' => Sms::class];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('sms', [
            'default' => env('SMS_CONNECTION', 'log'),
            'connections' => [
                'log' => [
                    'driver' => LogDriver::class,
                ],
                'null' => [
                    'driver' => NullDriver::class,
                ],
                'yunpian' => [
                    'driver' => YunPianDriver::class,
                    'api_key' => env('YUNPIAN_KEY'),
                ],
            ],
        ]);
    }
}
