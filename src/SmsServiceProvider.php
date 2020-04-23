<?php

namespace Zing\LaravelSms;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\ServiceProvider;
use Zing\LaravelSms\Channels\SmsChannel;
use Zing\LaravelSms\Facades\Sms;

class SmsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole() && function_exists('config_path')) {
            $this->publishes([
                __DIR__ . '/../config/sms.php' => config_path('sms.php'),
            ], 'config');
        }

        $this->mergeConfigFrom(__DIR__ . '/../config/sms.php', 'sms');
    }

    public function register()
    {
        Notification::resolved(function (ChannelManager $service) {
            $service->extend('sms', function (Application $app) {
                return $app->make(SmsChannel::class);
            });
        });
        $this->app->singleton('sms', function (Application $app) {
            return $app->make(SmsManager::class);
        });
        $this->app->alias('sms', Sms::class);
    }
}
