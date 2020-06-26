<?php

declare(strict_types=1);

namespace Zing\LaravelSms;

use Illuminate\Contracts\Container\Container;
use Illuminate\Foundation\Application as Laravel;
use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\ServiceProvider;
use Laravel\Lumen\Application as Lumen;
use Zing\LaravelSms\Channels\SmsChannel;
use Zing\LaravelSms\Commands\SmsSwitchConnectionCommand;
use Zing\LaravelSms\Facades\Sms;

class SmsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole() && $this->app instanceof Laravel) {
            $this->publishes(
                [
                    $this->getConfigPath() => config_path('sms.php'),
                ],
                'config'
            );
        }
    }

    public function register(): void
    {
        $this->registerConfig();
        Notification::resolved(
            function (ChannelManager $service): void {
                $service->extend(
                    'sms',
                    function (Container $app) {
                        return $app->make(SmsChannel::class);
                    }
                );
            }
        );
        $this->app->singleton(
            'sms',
            function (Container $app) {
                return $app->make(SmsManager::class);
            }
        );
        $this->app->alias('sms', Sms::class);
        $this->registerCommands();
    }

    protected function getConfigPath(): string
    {
        return __DIR__ . '/../config/sms.php';
    }

    protected function registerConfig(): void
    {
        if ($this->app instanceof Lumen) {
            $this->app->configure('sms');
        }

        $this->mergeConfigFrom($this->getConfigPath(), 'sms');
    }

    protected function registerCommands(): void
    {
        $this->app->singleton('command.sms.gateway', SmsSwitchConnectionCommand::class);
        $this->commands(
            [
                'command.sms.gateway',
            ]
        );
    }
}
