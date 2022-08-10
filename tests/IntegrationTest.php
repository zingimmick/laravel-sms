<?php

declare(strict_types=1);

namespace Zing\LaravelSms\Tests;

use Composer\ClassMapGenerator\ClassMapGenerator;
use Overtrue\EasySms\Contracts\GatewayInterface;
use Overtrue\EasySms\Exceptions\GatewayErrorException;
use Overtrue\EasySms\Gateways\ErrorlogGateway;
use Overtrue\EasySms\Gateways\HuaweiGateway;
use Overtrue\EasySms\Support\Config;
use Overtrue\EasySms\Traits\HasHttpRequest;
use ReflectionClass;
use Zing\LaravelSms\SmsMessage;
use Zing\LaravelSms\SmsNumber;

/**
 * @internal
 */
final class IntegrationTest extends TestCase
{
    protected function getEnvironmentSetUp($app): void
    {
    }

    public function testAllDriversImplementsGatewayInterface(): void
    {
        $drivers = collect((array) config('sms.connections'))
            ->pluck('driver');
        $drivers->each(
            static function ($driver): void {
                if (class_exists($driver)) {
                    $message = sprintf('%s should implements ', $driver) . GatewayInterface::class;
                    self::assertTrue(is_subclass_of($driver, GatewayInterface::class), $message);
                }
            }
        );
    }

    public function testAllDriversHasDefaultConfig(): void
    {
        $drivers = collect((array) config('sms.connections'))
            ->pluck('driver');
        $gateways = collect(ClassMapGenerator::createMap('vendor/overtrue/easy-sms'))
            ->keys()
            ->filter(
                static function ($name): bool {
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
        self::assertCount(0, $diff, $gateways->diff($drivers->sort()->values())->toJson());
    }

    private function getConnections(): \Illuminate\Support\Collection
    {
        return collect((array) config('sms.connections'))
            ->filter(
                static function ($config): bool {
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
            );
    }

    public function testSend(): void
    {
        $this->getConnections()
            ->each(function ($options): void {
                $this->expectOptions($options);
            });
    }

    /**
     * @param array<string, mixed> $options
     */
    private function expectOptions(array $options): void
    {
        /** @var \Overtrue\EasySms\Gateways\Gateway|\Mockery\MockInterface $gateway */
        $gateway = \Mockery::mock($options['driver'], [$options]);
        $gateway->makePartial();
        $gateway->shouldAllowMockingProtectedMethods()
            ->shouldReceive('request')
            ->withAnyArgs()
            ->andThrow(new GatewayErrorException('just for mock request', 0));
        $gateway->shouldReceive('setConfig')
            ->passthru();
        $config = $this->mockConfig($gateway, $options);
        $gateway->setConfig($config);

        try {
            $gateway->send(new SmsNumber('18888888888'), SmsMessage::text('test'), $config);
        } catch (GatewayErrorException $gatewayErrorException) {
            if (\in_array(HasHttpRequest::class, trait_uses_recursive(\get_class($gateway)), true)) {
                self::expectException(GatewayErrorException::class);
                self::expectExceptionMessage('just for mock request');
            }
        }
    }

    /**
     * @param \Overtrue\EasySms\Gateways\Gateway|\Mockery\MockInterface $gateway
     * @param array<string, mixed> $options
     *
     * @return \Overtrue\EasySms\Support\Config|\Mockery\MockInterface
     */
    private function mockConfig($gateway, array $options): \Mockery\LegacyMockInterface
    {
        $config = \Mockery::mock(Config::class, [$options]);
        foreach ($options as $name => $value) {
            $args = $this->formatArgs($gateway, $name, $value);

            $config->shouldReceive('get')
                ->withArgs($args)
                ->andReturn($value);
        }

        return $config;
    }

    /**
     * @param \Overtrue\EasySms\Gateways\Gateway|\Mockery\MockInterface $gateway
     * @param mixed $value
     * @return array|string[]
     */
    private function formatArgs($gateway, string $name, $value): array
    {
        if ($gateway instanceof ErrorlogGateway && $name === 'file') {
            return [$name, ''];
        }

        if ($gateway instanceof HuaweiGateway && $name === 'from' && \is_array($value)) {
           return [$name];
        }
        return $value === null ? [$name] : [$name, $value];
    }
}
