<?php

declare(strict_types=1);

namespace Zing\LaravelSms\Tests\Drivers;

use Illuminate\Support\Arr;
use Mockery;
use Overtrue\EasySms\PhoneNumber;
use Overtrue\EasySms\Support\Config;
use Zing\LaravelSms\Exceptions\CouldNotSendNotification;
use Zing\LaravelSms\Gateways\YunpianGateway;
use Zing\LaravelSms\SmsMessage;
use Zing\LaravelSms\Tests\TestCase;

class YunpianDriverTest extends TestCase
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
                    //成功发送的短信计费条数
                    'fee' => 0.05,
                    //扣费条数，70个字一条，超出70个字时按每67字一条计
                    'unit' => 'RMB',
                    // 计费单位
                    'mobile' => '18188888888',
                    // 发送手机号
                    'sid' => 3310228982,
                    // 短信ID
                ],
                [
                    'code' => 100,
                    'msg' => '发送失败',
                ]
            )->times(2);

        $message = SmsMessage::text('【test】This is a test message.');
        $config = new Config($config);
        $this->assertSame(
            [
                'code' => 0,
                'msg' => '发送成功',
                'count' => 1,
                //成功发送的短信计费条数
                'fee' => 0.05,
                //扣费条数，70个字一条，超出70个字时按每67字一条计
                'unit' => 'RMB',
                // 计费单位
                'mobile' => '18188888888',
                // 发送手机号
                'sid' => 3310228982,
                // 短信ID
            ],
            $driver->send(new PhoneNumber(18188888888), $message, $config)
        );

        $this->expectException(CouldNotSendNotification::class);
        $this->expectExceptionCode(100);
        $this->expectExceptionMessage('发送失败');

        $driver->send(new PhoneNumber(18188888888), $message, $config);
    }

    /**
     * @dataProvider provideNumberAndMessage
     *
     * @param mixed $number
     * @param mixed $message
     * @param mixed $expected
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
            //成功发送的短信计费条数
            'fee' => 0.05,
            //扣费条数，70个字一条，超出70个字时按每67字一条计
            'unit' => 'RMB',
            // 计费单位
            'mobile' => $number,
            // 发送手机号
            'sid' => 3310228982,
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

        $this->assertSame($response, $driver->send(new PhoneNumber($number), SmsMessage::text($message), $config));
    }

    public function testGetOptions(): void
    {
        $driver = Mockery::mock(YunpianGateway::class, [[]])->shouldAllowMockingProtectedMethods();
        $driver->shouldReceive('getBaseOptions')
            ->once()
            ->passthru();
        $driver->allows('getGuzzleOptions')
            ->passthru();
        $driver->allows('getBaseUri')
            ->passthru();
        $driver->allows('getTimeout')
            ->passthru();
        self::assertSame('http://yunpian.com', Arr::get($driver->getBaseOptions(), 'base_uri'));
    }

    /**
     * @return array<int, array<int|string>>
     */
    public function provideNumberAndMessage(): array
    {
        return [
            [18188888888, 'This is a 【test】 message.', '【default】This is a 【test】 message.'],
            [18188888888, '【custom】This is a 【test】 message.', '【custom】This is a 【test】 message.'],
        ];
    }
}
