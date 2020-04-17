<?php

namespace Zing\LaravelSms\Tests\Drivers;

use Illuminate\Support\Facades\Log;
use Zing\LaravelSms\Drivers\NullDriver;
use Zing\LaravelSms\Message;
use Zing\LaravelSms\PhoneNumber;
use Zing\LaravelSms\Tests\TestCase;

class NullDriverTest extends TestCase
{
    public function test_send()
    {
        $number = new PhoneNumber(18188888888);
        $message = Message::text('【test】This is a test message.');
        $driver = new NullDriver([]);
        Log::shouldReceive('debug')->withAnyArgs()->twice();
        $driver->send($number, $message);
    }
}
