<?php

namespace Zing\LaravelSms\Tests\Drivers;

use Mockery;
use Zing\LaravelSms\Drivers\YunpianDriver;
use Zing\LaravelSms\Exceptions\CouldNotSendNotification;
use Zing\LaravelSms\Message;
use Zing\LaravelSms\PhoneNumber;
use Zing\LaravelSms\Support\Config;
use Zing\LaravelSms\Tests\TestCase;

class YunpianDriverTest extends TestCase
{
    public function test_send()
    {
        $config = [
            'api_key' => 'mock-api-key',
        ];
        $driver = Mockery::mock(YunpianDriver::class . '[request]', [$config])->shouldAllowMockingProtectedMethods();

        $driver->shouldReceive('request')
            ->with('post', '/v1/sms/send.json', [
                'headers' => [],
                'form_params' => [
                    'apikey' => 'mock-api-key',
                    'mobile' => '18188888888',
                    'text' => '【overtrue】This is a test message.',
                ],
            ])->andReturn([
                'code' => 0,
                'msg' => '发送成功',
                'count' => 1, //成功发送的短信计费条数
                'fee' => 0.05,    //扣费条数，70个字一条，超出70个字时按每67字一条计
                'unit' => 'RMB',  // 计费单位
                'mobile' => '18188888888', // 发送手机号
                'sid' => 3310228982,   // 短信ID
            ], [
                'code' => 100,
                'msg' => '发送失败',
            ])->times(2);

        $message = Message::text('【overtrue】This is a test message.');
        $config = new Config($config);
        $this->assertSame([
            'code' => 0,
            'msg' => '发送成功',
            'count' => 1, //成功发送的短信计费条数
            'fee' => 0.05,    //扣费条数，70个字一条，超出70个字时按每67字一条计
            'unit' => 'RMB',  // 计费单位
            'mobile' => '18188888888', // 发送手机号
            'sid' => 3310228982,   // 短信ID
        ], $driver->send(new PhoneNumber(18188888888), $message, $config));

        $this->expectException(CouldNotSendNotification::class);
        $this->expectExceptionCode(100);
        $this->expectExceptionMessage('发送失败');

        $driver->send(new PhoneNumber(18188888888), $message, $config);
    }

    /**
     * @dataProvider provideNumberAndMessage
     */
    public function test_default_signature($number, $message, $expected)
    {
        $config = [
            'api_key' => 'mock-api-key',
            'signature' => '【测试】',
        ];
        $response = [
            'code' => 0,
            'msg' => '发送成功',
            'count' => 1, //成功发送的短信计费条数
            'fee' => 0.05,    //扣费条数，70个字一条，超出70个字时按每67字一条计
            'unit' => 'RMB',  // 计费单位
            'mobile' => $number, // 发送手机号
            'sid' => 3310228982,   // 短信ID
        ];

        $driver = Mockery::mock(YunpianDriver::class . '[request]', [$config])->shouldAllowMockingProtectedMethods();
        $config = new Config($config);
        $driver->shouldReceive('request')->with('post', '/v1/sms/send.json', [
            'headers' => [],  'form_params' => [
                'apikey' => 'mock-api-key',
                'mobile' => $number,
                'text' => $expected,
            ],
        ])->andReturn($response);

        $this->assertSame($response, $driver->send(new PhoneNumber($number), Message::text($message), $config));
    }

    public function provideNumberAndMessage()
    {
        return [
            [18188888888, 'This is a 【test】 message.', '【测试】This is a 【test】 message.'],
            [18188888888, '【已经存在】This is a 【test】 message.', '【已经存在】This is a 【test】 message.'],
        ];
    }
}
