<?php

declare(strict_types=1);

namespace Zing\LaravelSms\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class Sms.
 *
 * @method static \Zing\LaravelSms\Connectors\Connector connection(string $name = null)
 * @method static \Zing\LaravelSms\Connectors\Connector via(string $name = null)
 *
 * @mixin \Zing\LaravelSms\SmsManager
 */
class Sms extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'sms';
    }
}
