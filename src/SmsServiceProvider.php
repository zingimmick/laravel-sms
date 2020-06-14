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
                    __DIR__ . '/../config/sms.php' => config_path('sms.php'),
                ],
                'config'
            );
        }
    }

    public function register(): void
    {
        if ($this->app instanceof Lumen) {
            $this->app->configure('sms');
        }

        $this->mergeConfigFrom(__DIR__ . '/../config/sms.php', 'sms');
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
        $this->app->singleton('command.sms.gateway',SmsSwitchConnectionCommand::class);
        $this->commands([
            'command.sms.gateway'
        ]);
    }
}
