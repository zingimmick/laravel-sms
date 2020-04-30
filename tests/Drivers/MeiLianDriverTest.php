<?php

declare(strict_types=1);

namespace Zing\LaravelSms\Tests\Drivers;

use Mockery;
use Overtrue\EasySms\PhoneNumber;
use Overtrue\EasySms\Support\Config;
use Zing\LaravelSms\Exceptions\CouldNotSendNotification;
use Zing\LaravelSms\Gateways\MeilianGateway;
use Zing\LaravelSms\Message;
use Zing\LaravelSms\Tests\TestCase;

class MeiLianDriverTest extends TestCase
{
    public function testSend(): void
    {
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

        $message = Message::text('【test】This is a test message.');
        $config = new Config($config);
        $this->assertSame('success:Missing recipient', $driver->send(new PhoneNumber(18188888888), $message, $config));

        $this->expectException(CouldNotSendNotification::class);
        $this->expectExceptionCode(1);
        $this->expectExceptionMessage('error:Missing recipient');

        $driver->send(new PhoneNumber(18188888888), $message, $config);
    }

    public function testSend2(): void
    {
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

        $message = Message::text('【test】This is a test message.');
        $config = new Config($config);

        $this->expectException(CouldNotSendNotification::class);
        $this->expectExceptionMessage('meilian response does only seem to accept string.');

        $driver->send(new PhoneNumber(18188888888), $message, $config);
    }

    /**
     * @dataProvider provideNumberAndMessage
     *
     * @param mixed $number
     * @param mixed $message
     * @param mixed $expected
     */
    public function testDefaultSignature($number, $message, $expected): void
    {
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

        $this->assertSame($response, $driver->send(new PhoneNumber($number), Message::text($message), $config));
    }

    public function provideNumberAndMessage()
    {
        return [
            [18188888888, 'This is a 【test】 message.', '【test】This is a 【test】 message.'],
            [18188888888, '【custom】This is a 【test】 message.', '【custom】This is a 【test】 message.'],
        ];
    }
}
