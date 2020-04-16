<?php

namespace Zing\LaravelSms\Tests;

use Zing\LaravelSms\Drivers\LogDriver;
use Zing\LaravelSms\Drivers\NullDriver;
use Zing\LaravelSms\Message;

class MessageTest extends TestCase
{
    public function test_type()
    {
        $message = Message::text('');
        self::assertSame(\Zing\LaravelSms\Contracts\Message::TEXT, $message->getType());
        $message = Message::voice('');
        self::assertSame(\Zing\LaravelSms\Contracts\Message::VOICE, $message->getType());
    }

    public function test_content()
    {
        $message = Message::text('test');
        self::assertSame('test', $message->getContent());
        $message->withContent('custom');
        self::assertSame('custom', $message->getContent());
        $callback = function () {
            return 'test';
        };
        $message->withContent($callback);
        self::assertSame($callback(), $message->getContent());
        $logDriver = new LogDriver([]);
        $nullDriver = new NullDriver([]);
        $callbackWithDriver = function ($driver) {
            if ($driver instanceof LogDriver) {
                return 'log';
            }

            return 'test';
        };
        $message->withContent($callbackWithDriver);
        self::assertSame('log', $message->getContent($logDriver));
        self::assertSame('test', $message->getContent($nullDriver));
    }

    public function test_template()
    {
        $message = Message::fromTemplate('test', []);
        self::assertSame('test', $message->getTemplate());
        $message->withTemplate('custom');
        self::assertSame('custom', $message->getTemplate());
        $callback = function () {
            return 'test';
        };
        $message->withTemplate($callback);
        self::assertSame($callback(), $message->getTemplate());
        $logDriver = new LogDriver([]);
        $nullDriver = new NullDriver([]);
        $callbackWithDriver = function ($driver) {
            if ($driver instanceof LogDriver) {
                return 'log';
            }

            return 'test';
        };
        $message->withTemplate($callbackWithDriver);
        self::assertSame('log', $message->getTemplate($logDriver));
        self::assertSame('test', $message->getTemplate($nullDriver));
    }

    public function test_data()
    {
        $message = Message::fromTemplate('test', ['test']);
        self::assertSame(['test'], $message->getData());
        $message->withData(['custom']);
        self::assertSame(['custom'], $message->getData());
        $callback = function () {
            return ['test'];
        };
        $message->withData($callback);
        self::assertSame($callback(), $message->getData());
        $logDriver = new LogDriver([]);
        $nullDriver = new NullDriver([]);
        $callbackWithDriver = function ($driver) {
            if ($driver instanceof LogDriver) {
                return ['log'];
            }

            return ['test'];
        };
        $message->withData($callbackWithDriver);
        self::assertSame(['log'], $message->getData($logDriver));
        self::assertSame(['test'], $message->getData($nullDriver));
    }
}
