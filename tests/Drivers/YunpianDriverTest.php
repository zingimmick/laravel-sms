<?php

declare(strict_types=1);

namespace Zing\LaravelSms\Tests\Drivers;

use Mockery;
use Overtrue\EasySms\PhoneNumber;
use Overtrue\EasySms\Support\Config;
use Zing\LaravelSms\Exceptions\CouldNotSendNotification;
use Zing\LaravelSms\Gateways\YunpianGateway;
use Zing\LaravelSms\SmsMessage;
use Zing\LaravelSms\Tests\TestCase;

/**
 * @internal
 */
final class YunpianDriverTest extends TestCase
{
    public function testSend(): void
    {
        $config = [
            'api_key' => 'mock-api-key',
        ];
        $driver = Mockery::mock(YunpianGateway::class . '[request]', [$config])->shouldAllowMockingProtectedMethods();

        $driver->shouldReceive('request')
            ->with(
                'post',
                '/v1/sms/send.json',
                [
                    'headers' => [],
                    'form_params' => [
                        'apikey' => 'mock-api-key',
                        'mobile' => '18188888888',
                        'text' => '【test】This is a test message.',
                    ],
                ]
            )->andReturn(
                [
                    'code' => 0,
                    'msg' => '发送成功',
                    'count' => 1,
                    // 成功发送的短信计费条数
                    'fee' => 0.05,
                    // 扣费条数，70个字一条，超出70个字时按每67字一条计
                    'unit' => 'RMB',
                    // 计费单位
                    'mobile' => '18188888888',
                    // 发送手机号
                    'sid' => 3_310_228_982,
                    // 短信ID
                ],
                [
                    'code' => 100,
                    'msg' => '发送失败',
                ]
            )->times(2);

        $message = SmsMessage::text('【test】This is a test message.');
        $config = new Config($config);
        self::assertSame(
            [
                'code' => 0,
                'msg' => '发送成功',
                'count' => 1,
                // 成功发送的短信计费条数
                'fee' => 0.05,
                // 扣费条数，70个字一条，超出70个字时按每67字一条计
                'unit' => 'RMB',
                // 计费单位
                'mobile' => '18188888888',
                // 发送手机号
                'sid' => 3_310_228_982,
                // 短信ID
            ],
            $driver->send(new PhoneNumber(18_188_888_888), $message, $config)
        );

        $this->expectException(CouldNotSendNotification::class);
        $this->expectExceptionCode(100);
        $this->expectExceptionMessage('发送失败');

        $driver->send(new PhoneNumber(18_188_888_888), $message, $config);
    }

    /**
     * @dataProvider provideNumberAndMessage
     */
    public function testDefaultSignature(int $number, string $message, string $expected): void
    {
        $config = [
            'api_key' => 'mock-api-key',
            'signature' => '【default】',
        ];
        $response = [
            'code' => 0,
            'msg' => '发送成功',
            'count' => 1,
            // 成功发送的短信计费条数
            'fee' => 0.05,
            // 扣费条数，70个字一条，超出70个字时按每67字一条计
            'unit' => 'RMB',
            // 计费单位
            'mobile' => $number,
            // 发送手机号
            'sid' => 3_310_228_982,
            // 短信ID
        ];

        $driver = Mockery::mock(YunpianGateway::class . '[request]', [$config])->shouldAllowMockingProtectedMethods();
        $driver->shouldReceive('request')
            ->with(
                'post',
                '/v1/sms/send.json',
                [
                    'headers' => [],
                    'form_params' => [
                        'apikey' => 'mock-api-key',
                        'mobile' => $number,
                        'text' => $expected,
                    ],
                ]
            )->andReturn($response);
        $config = new Config($config);

        self::assertSame($response, $driver->send(new PhoneNumber($number), SmsMessage::text($message), $config));
    }

    public function testGetOptions(): void
    {
        $driver = Mockery::mock(YunpianGateway::class, [[]])->shouldAllowMockingProtectedMethods();
        $driver->allows('getBaseUri')
            ->passthru();
        self::assertSame('http://yunpian.com', $driver->getBaseUri());
    }

    /**
     * @return \Iterator<array{string|int|\Overtrue\EasySms\Contracts\PhoneNumberInterface, string|\Overtrue\EasySms\Contracts\MessageInterface, string|\Overtrue\EasySms\Contracts\MessageInterface}>
     */
    public function provideNumberAndMessage(): \Iterator
    {
        yield [18_188_888_888, 'This is a 【test】 message.', '【default】This is a 【test】 message.'];

        yield [
            18_188_888_888,
            '【custom】This is a 【test】 message.',
            '【custom】This is a 【test】 message.',
        ];
    }
}
