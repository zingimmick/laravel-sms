<?php

declare(strict_types=1);

namespace Zing\LaravelSms\Tests;

use Composer\Autoload\ClassMapGenerator;
use Overtrue\EasySms\Contracts\GatewayInterface;
use ReflectionClass;

class IntegrationTest extends TestCase
{
    protected function getEnvironmentSetUp($app): void
    {
    }

    public function testAllDriversImplementsGatewayInterface(): void
    {
        $drivers = collect(config('sms.connections'))->pluck('driver');
        $drivers->each(
            function ($driver): void {
                if (class_exists($driver)) {
                    $message = "{$driver} should implements " . GatewayInterface::class;
                    self::assertTrue(is_subclass_of($driver, GatewayInterface::class), $message);
                }
            }
        );
    }

    public function testAllDriversHasDefaultConfig(): void
    {
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
        self::assertCount(0, $diff, $gateways->diff($drivers->sort()->values())->toJson());
    }
}
