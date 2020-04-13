<?php

namespace Zing\LaravelSms\Tests\Drivers;

use Zing\LaravelSms\Drivers\MeilianDriver;
use Zing\LaravelSms\Exceptions\CouldNotSendNotification;
use Zing\LaravelSms\Message;
use Zing\LaravelSms\PhoneNumber;
use Zing\LaravelSms\Support\Config;
use Zing\LaravelSms\Tests\TestCase;

class MeiLianDriverTest extends TestCase
{
    public function test_send()
    {
        $config = [
            'username' => 'mock-username',
            'password' => 'mock-password',
            'api_key' => 'mock-api-key',
        ];
        $driver = \Mockery::mock(MeilianDriver::class . '[request]', [$config])->shouldAllowMockingProtectedMethods();

        $driver->shouldReceive('request')
            ->with('post', 'http://m.5c.com.cn/api/send/index.php', [
                'headers' => [],
                'form_params' => [
                    'username' => 'mock-username',
                    'password' => 'mock-password',
                    'apikey' => 'mock-api-key',
                    'mobile' => '18188888888',
                    'content' => '【overtrue】This is a test message.',
                ],
            ])->andReturn('success:Missing recipient', 'error:Missing recipient')->times(2);

        $message = Message::text('【overtrue】This is a test message.');
        $config = new Config($config);
        $this->assertSame('success:Missing recipient', $driver->send(new PhoneNumber(18188888888), $message, $config));

        $this->expectException(CouldNotSendNotification::class);
        $this->expectExceptionCode(1);
        $this->expectExceptionMessage('error:Missing recipient');

        $driver->send(new PhoneNumber(18188888888), $message, $config);
    }

    /**
     * @dataProvider provideNumberAndMessage
     */
    public function test_default_signature($number, $message, $expected)
    {
        $config = [
            'username' => 'mock-username',
            'password' => 'mock-password',
            'api_key' => 'mock-api-key',
            'signature' => '【测试】',
        ];
        $response = 'success:Missing recipient';

        $driver = \Mockery::mock(MeilianDriver::class . '[request]', [$config])->shouldAllowMockingProtectedMethods();
        $config = new Config($config);
        $driver->shouldReceive('request')->with('post', 'http://m.5c.com.cn/api/send/index.php', [
            'headers' => [],
            'form_params' => [
                'username' => 'mock-username',
                'password' => 'mock-password',
                'apikey' => 'mock-api-key',
                'mobile' => $number,
                'content' => $expected,
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
