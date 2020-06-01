<?php

declare(strict_types=1);

namespace Zing\LaravelSms\Tests\Lumen;

use Illuminate\Notifications\NotificationServiceProvider;
use Zing\LaravelSms\SmsServiceProvider;
use Zing\LaravelSms\Tests\Concerns\ServiceProviderTests;

class ServiceProviderTest extends LumenTestCase
{
    use ServiceProviderTests;

    public function createApplication()
    {
        return require __DIR__ . '/lumen.php';
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->app->withFacades(true);
        $this->app->register(NotificationServiceProvider::class);
        $this->app->register(SmsServiceProvider::class);
    }

    protected function createAdminUser(): void
    {
    }
}
