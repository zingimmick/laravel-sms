<?php

declare(strict_types=1);

namespace Zing\LaravelSms\Tests\Drivers;

use Mockery;
use Overtrue\EasySms\PhoneNumber;
use Overtrue\EasySms\Support\Config;
use Zing\LaravelSms\Exceptions\CouldNotSendNotification;
use Zing\LaravelSms\Gateways\MeilianGateway;
use Zing\LaravelSms\SmsMessage;

it(
    'can send message',
    function (): void {
        $config = [
            'username' => 'mock-username',
            'password' => 'mock-password',
            'api_key' => 'mock-api-key',
        ];
        $driver = Mockery::mock(MeilianGateway::class . '[request]', [$config])->shouldAllowMockingProtectedMethods();

        $driver->shouldReceive('request')
            ->with(
                'post',
                'http://m.5c.com.cn/api/send/index.php',
                [
                    'headers' => [],
                    'form_params' => [
                        'username' => 'mock-username',
                        'password' => 'mock-password',
                        'apikey' => 'mock-api-key',
                        'mobile' => '18188888888',
                        'content' => '【test】This is a test message.',
                    ],
                ]
            )
            ->andReturn('success:Missing recipient', 'error:Missing recipient')
            ->times(2);

        $message = SmsMessage::text('【test】This is a test message.');
        $config = new Config($config);
        $this->assertSame('success:Missing recipient', $driver->send(new PhoneNumber(18188888888), $message, $config));

        $this->expectException(CouldNotSendNotification::class);
        $this->expectExceptionCode(1);
        $this->expectExceptionMessage('error:Missing recipient');

        $driver->send(new PhoneNumber(18188888888), $message, $config);
    }
);
it(
    'will throw exception',
    function (): void {
        $config = [
            'username' => 'mock-username',
            'password' => 'mock-password',
            'api_key' => 'mock-api-key',
        ];
        $driver = Mockery::mock(MeilianGateway::class . '[request]', [$config])->shouldAllowMockingProtectedMethods();

        $driver->shouldReceive('request')
            ->with(
                'post',
                'http://m.5c.com.cn/api/send/index.php',
                [
                    'headers' => [],
                    'form_params' => [
                        'username' => 'mock-username',
                        'password' => 'mock-password',
                        'apikey' => 'mock-api-key',
                        'mobile' => '18188888888',
                        'content' => '【test】This is a test message.',
                    ],
                ]
            )
            ->andReturn(['test'])
            ->times(1);

        $message = SmsMessage::text('【test】This is a test message.');
        $config = new Config($config);

        $this->expectException(CouldNotSendNotification::class);
        $this->expectExceptionMessage('meilian response does only seem to accept string.');

        $driver->send(new PhoneNumber(18188888888), $message, $config);
    }
);

it(
    'can send with default signature',
    function ($number, $message, $expected): void {
        $config = [
            'username' => 'mock-username',
            'password' => 'mock-password',
            'api_key' => 'mock-api-key',
            'signature' => '【test】',
        ];
        $response = 'success:Missing recipient';

        $driver = Mockery::mock(MeilianGateway::class . '[request]', [$config])->shouldAllowMockingProtectedMethods();
        $config = new Config($config);
        $driver->shouldReceive('request')->with(
            'post',
            'http://m.5c.com.cn/api/send/index.php',
            [
                'headers' => [],
                'form_params' => [
                    'username' => 'mock-username',
                    'password' => 'mock-password',
                    'apikey' => 'mock-api-key',
                    'mobile' => $number,
                    'content' => $expected,
                ],
            ]
        )->andReturn($response);

        $this->assertSame($response, $driver->send(new PhoneNumber($number), SmsMessage::text($message), $config));
    }
)->with(
    [
        [18188888888, 'This is a 【test】 message.', '【test】This is a 【test】 message.'],
        [18188888888, '【custom】This is a 【test】 message.', '【custom】This is a 【test】 message.'],
    ]
);
