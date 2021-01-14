<?php

declare(strict_types=1);

namespace Zing\LaravelSms\Tests;

use Composer\Autoload\ClassMapGenerator;
use Overtrue\EasySms\Contracts\GatewayInterface;
use Overtrue\EasySms\Exceptions\GatewayErrorException;
use Overtrue\EasySms\Support\Config;
use Overtrue\EasySms\Traits\HasHttpRequest;
use ReflectionClass;
use Zing\LaravelSms\SmsMessage;
use Zing\LaravelSms\SmsNumber;
use function PHPUnit\Framework\assertCount;
use function PHPUnit\Framework\assertTrue;

uses(DefaultConfigTestCase::class);
it(
    'all drivers implements gateway interface',
    function (): void {
        $drivers = collect(config('sms.connections'))->pluck('driver');
        $drivers->each(
            function ($driver): void {
                if (class_exists($driver)) {
                    $message = "{$driver} should implements " . GatewayInterface::class;
                    assertTrue(is_subclass_of($driver, GatewayInterface::class), $message);
                }
            }
        );
    }
);
it(
    'all drivers has default config',
    function (): void {
        $drivers = collect(config('sms.connections'))->pluck('driver');
        $gateways = collect(ClassMapGenerator::createMap('vendor/overtrue/easy-sms'))
            ->keys()
            ->filter(
                function ($name) {
                    if (! class_exists($name)) {
                        return false;
                    }

                    $reflectionClass = new ReflectionClass($name);
                    if (! $reflectionClass->isSubclassOf(GatewayInterface::class)) {
                        return false;
                    }

                    return $reflectionClass->isInstantiable();
                }
            )
            ->sort()
            ->values();
        $diff = $gateways->diff($drivers->sort()->values());
        assertCount(0, $diff, $gateways->diff($drivers->sort()->values())->toJson());
    }
);
it(
    'send',
    function (): void {
        collect(config('sms.connections'))
            ->filter(
                function ($config) {
                    $name = $config['driver'];
                    if (! class_exists($name)) {
                        return false;
                    }

                    $reflectionClass = new ReflectionClass($name);
                    if (! $reflectionClass->isSubclassOf(GatewayInterface::class)) {
                        return false;
                    }

                    return $reflectionClass->isInstantiable();
                }
            )
            ->each(
                function ($config): void {
                    $gateway = \Mockery::mock($config['driver'], [$config]);
                    $gateway->makePartial();
                    $gateway->shouldAllowMockingProtectedMethods()
                        ->shouldReceive('request')
                        ->withAnyArgs()
                        ->andThrow(new GatewayErrorException('just for mock request', 0));

                    try {
                        $gateway->send(new SmsNumber('18888888888'), SmsMessage::text('This is a test message.'), new Config($config));
                    } catch (GatewayErrorException $gatewayErrorException) {
                        if (in_array(HasHttpRequest::class, trait_uses_recursive($gateway), true)) {
                            $this->expectException(GatewayErrorException::class);
                            $this->expectExceptionMessage('just for mock request');
                        }
                    }
                }
            );
    }
);
