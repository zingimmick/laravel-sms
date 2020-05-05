<?php

declare(strict_types=1);

namespace Zing\LaravelSms\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use PHPUnit\Framework\Constraint\IsEqual;
use Zing\LaravelSms\Facades\Sms;
use Zing\LaravelSms\Gateways\LogGateway;
use Zing\LaravelSms\Gateways\NullGateway;
use Zing\LaravelSms\Gateways\YunpianGateway;
use Zing\LaravelSms\SmsServiceProvider;

class TestCase extends BaseTestCase
{
    public static function assertSameMessage($expected, $actual, string $message = ''): void
    {
        static::assertThat($actual, new IsEqual($expected), $message);
    }

    protected function getPackageProviders($app)
    {
        return [
            SmsServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app)
    {
        return ['Sms' => Sms::class];
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set(
            'sms',
            [
                'default' => env('SMS_CONNECTION', 'log'),
                'connections' => [
                    'log' => [
                        'driver' => LogGateway::class,
                    ],
                    'null' => [
                        'driver' => NullGateway::class,
                    ],
                    'yunpian' => [
                        'driver' => YunpianGateway::class,
                        'api_key' => env('YUNPIAN_KEY'),
                    ],
                ],
            ]
        );
    }
}
