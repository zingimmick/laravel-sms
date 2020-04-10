<?php
/**
 * Created by PhpStorm.
 * User: liuning
 * Date: 2018/12/17
 * Time: 10:35 AM.
 */

namespace Zing\LaravelSms;

use Illuminate\Support\Facades\Notification;
use Illuminate\Support\ServiceProvider;
use Zing\LaravelSms\Channels\SmsChannel;

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
        $this->app->singleton('sms', function ($app) {
            return new SmsManager($app);
        });
        Notification::extend('sms', function ($app) {
            return $app->make(SmsChannel::class);
        });
    }
}
