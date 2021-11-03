<?php

declare(strict_types=1);

namespace Zing\LaravelSms\Tests;

use Zing\LaravelSms\Commands\SmsSwitchConnectionCommand;
use function config;

class CommandTest extends TestCase
{
    public function testCommand(): void
    {
        $this->artisan(SmsSwitchConnectionCommand::class, [
            'connection' => 'default',
        ])->assertExitCode(0);
        $this->artisan(
            SmsSwitchConnectionCommand::class,
            [
                'connection' => 'default',
                '--show' => 1,
            ]
        )->assertExitCode(0);
        file_put_contents($this->envPath(), '');
        $this->artisan(SmsSwitchConnectionCommand::class, [
            'connection' => 'default',
        ])->assertExitCode(0);
        $this->artisan(SmsSwitchConnectionCommand::class, [
            'connection' => 'default-2',
        ])
            ->expectsQuestion(
                'This maybe invalidate existing sms feature. Are you sure you want to override the sms default connection?',
                false
            )
            ->assertExitCode(0);
        self::assertSame(config('sms.default'), 'default');
        $this->artisan(SmsSwitchConnectionCommand::class, [
            'connection' => 'default-2',
        ])->expectsQuestion(
            'This maybe invalidate existing sms feature. Are you sure you want to override the sms default connection?',
            true
        )
            ->assertExitCode(0);
        self::assertSame(config('sms.default'), 'default-2');
    }

    public function testAlwaysNo(): void
    {
        file_put_contents($this->envPath(), '');
        $this->artisan(SmsSwitchConnectionCommand::class, [
            'connection' => 'default',
        ])->assertExitCode(0);
        $this->artisan(
            SmsSwitchConnectionCommand::class,
            [
                'connection' => 'default-2',
                '--always-no' => 1,
            ]
        )->expectsOutput('Sms default connection already exists. Skipping...');
        self::assertSame(config('sms.default'), 'default');
    }

    protected function envPath(): string
    {
        if (method_exists($this->app, 'environmentFilePath')) {
            return $this->app->environmentFilePath();
        }

        return $this->app->basePath('.env');
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->connection = config('sms.default');
    }

    /**
     * @var string
     */
    protected $connection;

    protected function tearDown(): void
    {
        if (file_exists($this->envPath())) {
            unlink($this->envPath());
        }

        config([
            'sms.default' => $this->connection,
        ]);

        parent::tearDown();
    }
}
