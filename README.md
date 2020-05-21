# Laravel Sms

<p align="center">
<a href="https://github.com/zingimmick/laravel-sms/actions"><img src="https://github.com/zingimmick/laravel-sms/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://codecov.io/gh/zingimmick/laravel-sms"><img src="https://codecov.io/gh/zingimmick/laravel-sms/branch/master/graph/badge.svg" alt="Code Coverage" /></a>
<a href="https://packagist.org/packages/zing/laravel-sms"><img src="https://poser.pugx.org/zing/laravel-sms/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/zing/laravel-sms"><img src="https://poser.pugx.org/zing/laravel-sms/downloads" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/zing/laravel-sms"><img src="https://poser.pugx.org/zing/laravel-sms/v/unstable.svg" alt="Latest Unstable Version"></a>
<a href="https://packagist.org/packages/zing/laravel-sms"><img src="https://poser.pugx.org/zing/laravel-sms/license" alt="License"></a>
<a href="https://scrutinizer-ci.com/g/zingimmick/laravel-sms"><img src="https://scrutinizer-ci.com/g/zingimmick/laravel-sms/badges/quality-score.png" alt="Scrutinizer Code Quality"></a>
<a href="https://github.styleci.io/repos/254559831"><img src="https://github.styleci.io/repos/254559831/shield?branch=master" alt="StyleCI Shield"></a>
</p>

Laravel Sms is used to notify via sms and send a message.

## Installation

### Composer

Execute the following command to get the latest version of the package:

```terminal
composer require zing/laravel-sms
```

### Laravel

Publish Configuration

```shell
php artisan vendor:publish --provider "Zing\LaravelSms\SmsServiceProvider"
```

### Add Connections

This package based on [overtrue/easy-sms](https://github.com/overtrue/easy-sms), driver is the gateway.

## Usage

### Channel

#### Create a Notification

```php
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class Verification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $code;

    /**
     * Verification constructor.
     *
     * @param $code
     */
    public function __construct($code)
    {
        $this->code = $code;
    }

    public function via()
    {
        return ['sms'];
    }

    public function toSms($notifiable)
    {
        return "验证码 {$this->code}，您正在进行身份验证，打死也不要告诉别人哦!";
    }
}
```

#### Add notification route for sms to your notifiable

```php
use Illuminate\Notifications\Notifiable;

class User
{
    use Notifiable;

    public function routeNotificationForSms($notification)
    {
        return $this->phone;
    }
}
```

#### Send notification

```php
use Illuminate\Support\Facades\Notification;

$user = new User();
// use Notifiable Trait
$user->notify(new Verification('1111'));
// use Notification Facade
Notification::send($user, new Verification('1111'));
```

#### Send to anonymous notifiable

```php
use Illuminate\Support\Facades\Notification;
use Zing\LaravelSms\SmsNumber;
use Zing\LaravelSms\Channels\SmsChannel;

// use channel class name
Notification::route(SmsChannel::class, new SmsNumber(18188888888, 86))->notify(new Verification('1111'));
// use channel alias
Notification::route('sms', new SmsNumber(18188888888, 86))->notify(new Verification('1111'));
```

### Facade

#### Send Message

```php
use Zing\LaravelSms\Facades\Sms;

// use default connection
Sms::send(18188888888, 'test message.');
// use specific connection
Sms::connection('null')->send(18188888888, 'test message.');
```

## Specific usage

### Use specific connection for notification

**NOTE:** Only support for `Zing\LaravelSms\Message`

```php
 use Zing\LaravelSms\SmsMessage;

public function toSms($notifiable)
{
    return (new SmsMessage())->onConnection('log');
}
```

### Make PhoneNumber notifiable

**NOTE:** Only support for `Zing\LaravelSms\PhoneNumber`

```php
use Zing\LaravelSms\SmsNumber;

(new SmsNumber(18188888888))->notify(new Verification('1111'));
```



