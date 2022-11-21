<?php

declare(strict_types=1);

namespace Zing\LaravelSms\Tests;

use Illuminate\Support\Facades\Config;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Overtrue\EasySms\Contracts\MessageInterface;
use PHPUnit\Framework\Constraint\IsEqual;
use Zing\LaravelSms\Facades\Sms;
use Zing\LaravelSms\Gateways\LogGateway;
use Zing\LaravelSms\Gateways\NullGateway;
use Zing\LaravelSms\Gateways\YunpianGateway;
use Zing\LaravelSms\SmsServiceProvider;

abstract class TestCase extends BaseTestCase
{
    /**
     * @var string
     */
    private const DRIVER = 'driver';

    public static function assertSameMessage(
        MessageInterface|string $expected,
        MessageInterface|string $actual,
        string $message = ''
    ): void {
        static::assertThat($actual, new IsEqual($expected), $message);
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array<class-string<\Illuminate\Support\ServiceProvider>>
     */
    protected function getPackageProviders($app): array
    {
        return [SmsServiceProvider::class];
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array{Sms: class-string<\Zing\LaravelSms\Facades\Sms>}
     */
    protected function getPackageAliases($app): array
    {
        return [
            'Sms' => Sms::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        Config::set(
            'sms',
            [
                'default' => env('SMS_CONNECTION', 'log'),
                'connections' => [
                    'log' => [
                        self::DRIVER => LogGateway::class,
                    ],
                    'null' => [
                        self::DRIVER => NullGateway::class,
                    ],
                    'yunpian' => [
                        self::DRIVER => YunpianGateway::class,
                        'api_key' => env('YUNPIAN_KEY'),
                    ],
                ],
            ]
        );
    }
}
