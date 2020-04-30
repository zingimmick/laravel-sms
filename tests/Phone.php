<?php

declare(strict_types=1);

namespace Zing\LaravelSms\Tests;

use Illuminate\Notifications\Notifiable;

class Phone
{
    use Notifiable;

    protected $phone;

    /**
     * Phone constructor.
     *
     * @param string $phone
     */
    public function __construct($phone)
    {
        $this->phone = $phone;
    }

    public function getKey()
    {
        return $this->phone;
    }

    public function routeNotificationForSms($notification)
    {
        return $this->phone;
    }
}
