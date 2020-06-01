<?php

declare(strict_types=1);

namespace Zing\LaravelSms\Tests\Lumen;

use Illuminate\Log\LogServiceProvider;
use Illuminate\Notifications\NotificationServiceProvider;
use Laravel\Lumen\Testing\TestCase;
use Zing\LaravelSms\SmsServiceProvider;

class LumenTestCase extends TestCase
{
    public function createApplication()
    {
        return require __DIR__ . '/lumen.php';
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->app->withFacades(true);
        $this->app->register(LogServiceProvider::class);
        $this->app->register(NotificationServiceProvider::class);
        $this->app->register(SmsServiceProvider::class);
    }

    protected function createAdminUser(): void
    {
    }
}
