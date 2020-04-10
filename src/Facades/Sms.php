<?php
/**
 * Created by PhpStorm.
 * User: liuning
 * Date: 2018/12/17
 * Time: 11:17 AM.
 */

namespace Zing\LaravelSms\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class Sms.
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
