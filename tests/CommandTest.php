<?php

declare(strict_types=1);

namespace Zing\LaravelSms\Tests;

use Zing\LaravelSms\Commands\SmsSwitchConnectionCommand;
use function config;
use function PHPUnit\Framework\assertSame;

it(
    'command',
    function (): void {
        $this->artisan(
            SmsSwitchConnectionCommand::class,
            [
                'connection' => 'default',
            ]
        )->assertExitCode(0);
        $this->artisan(
            SmsSwitchConnectionCommand::class,
            [
                'connection' => 'default',
                '--show' => 1,
            ]
        )->assertExitCode(0);
        file_put_contents(envPath(app()), '');
        $this->artisan(
            SmsSwitchConnectionCommand::class,
            [
                'connection' => 'default',
            ]
        )->assertExitCode(0);
        $this->artisan(
            SmsSwitchConnectionCommand::class,
            [
                'connection' => 'default-2',
            ]
        )
            ->expectsQuestion('This maybe invalidate existing sms feature. Are you sure you want to override the sms default connection?', false)
            ->assertExitCode(0);
        assertSame(config('sms.default'), 'default');
        $this->artisan(
            SmsSwitchConnectionCommand::class,
            [
                'connection' => 'default-2',
            ]
        )->expectsQuestion('This maybe invalidate existing sms feature. Are you sure you want to override the sms default connection?', true)
            ->assertExitCode(0);
        assertSame(config('sms.default'), 'default-2');
    }
);
it(
    'always no',
    function (): void {
        file_put_contents(envPath(app()), '');
        $this->artisan(
            SmsSwitchConnectionCommand::class,
            [
                'connection' => 'default',
            ]
        )->assertExitCode(0);
        $this->artisan(
            SmsSwitchConnectionCommand::class,
            [
                'connection' => 'default-2',
                '--always-no' => 1,
            ]
        )->expectsOutput('Sms default connection already exists. Skipping...');
        assertSame(config('sms.default'), 'default');
    }
);
beforeEach(
    function (): void {
        $this->connection = config('sms.default');
    }
);
afterEach(
    function (): void {
        if (file_exists(envPath(app()))) {
            unlink(envPath(app()));
        }

        config(
            [
                'sms.default' => $this->connection,
            ]
        );
    }
);
