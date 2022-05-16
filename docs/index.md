## Laravel Sms

Laravel Sms is used to notify via sms and send a message.

[![Build Status](https://github.com/zingimmick/laravel-sms/actions/workflows/tests.yml/badge.svg?branch=5.x)](https://github.com/zingimmick/laravel-sms/actions/workflows/tests.yml)
[![Code Coverage](https://codecov.io/gh/zingimmick/laravel-sms/branch/5.x/graph/badge.svg)](https://codecov.io/gh/zingimmick/laravel-sms)
[![Latest Stable Version](https://poser.pugx.org/zing/laravel-sms/v/stable.svg)](https://packagist.org/packages/zing/laravel-sms)
[![Total Downloads](https://poser.pugx.org/zing/laravel-sms/downloads)](https://packagist.org/packages/zing/laravel-sms)
[![Latest Unstable Version](https://poser.pugx.org/zing/laravel-sms/v/unstable.svg)](https://packagist.org/packages/zing/laravel-sms)
[![License](https://poser.pugx.org/zing/laravel-sms/license)](https://packagist.org/packages/zing/laravel-sms)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/zingimmick/laravel-sms/badges/quality-score.png?b=5.x)](https://scrutinizer-ci.com/g/zingimmick/laravel-sms)
[![StyleCI Shield](https://github.styleci.io/repos/254559831/shield?branch=5.x)](https://github.styleci.io/repos/254559831)
[![Code Climate](https://api.codeclimate.com/v1/badges/9c81b0c9cdebc23ba26f/maintainability)](https://codeclimate.com/github/zingimmick/laravel-sms/maintainability)
[![FOSSA Status](https://app.fossa.com/api/projects/git%2Bgithub.com%2Fzingimmick%2Flaravel-sms.svg?type=shield)](https://app.fossa.com/projects/git%2Bgithub.com%2Fzingimmick%2Flaravel-sms?ref=badge_shield)

## Thanks

Many thanks to:

* [JetBrains](https://www.jetbrains.com/?from=LaravelSms) for the excellent
  **PhpStorm IDE** and providing me with an open source license to speed up the
  project development.
 
  [![JetBrains](/docs/jetbrains.svg)](https://www.jetbrains.com/?from=LaravelSms)

## Requirement

1. PHP >= 7.3
2. [Composer](https://getcomposer.org)
3. `ext-json`

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
// or
Sms::via('null')->send(18188888888, 'test message.');
```

## Specific usage

### Use specific connection for notification

**NOTE:** Only support for `Zing\LaravelSms\SmsMessage`

```php
use Zing\LaravelSms\SmsMessage;

public function toSms($notifiable)
{
    return (new SmsMessage())->onConnection('log');
}
```

### Make PhoneNumber notifiable

**NOTE:** Only support for `Zing\LaravelSms\SmsNumber`

```php
use Zing\LaravelSms\SmsNumber;

(new SmsNumber(18188888888))->notify(new Verification('1111'));
```

## License

Laravel Sms is open-sourced software licensed under the [MIT license](LICENSE).



[![FOSSA Status](https://app.fossa.com/api/projects/git%2Bgithub.com%2Fzingimmick%2Flaravel-sms.svg?type=large)](https://app.fossa.com/projects/git%2Bgithub.com%2Fzingimmick%2Flaravel-sms?ref=badge_large)

<!--
### Markdown

Markdown is a lightweight and easy-to-use syntax for styling your writing. It includes conventions for

```markdown
Syntax highlighted code block

# Header 1
## Header 2
### Header 3

- Bulleted
- List

1. Numbered
2. List

**Bold** and _Italic_ and `Code` text

[Link](url) and ![Image](src)
```

For more details see [GitHub Flavored Markdown](https://guides.github.com/features/mastering-markdown/).

### Jekyll Themes

Your Pages site will use the layout and styles from the Jekyll theme you have selected in your [repository settings](https://github.com/zingimmick/laravel-sms/settings/pages). The name of this theme is saved in the Jekyll `_config.yml` configuration file.

### Support or Contact

Having trouble with Pages? Check out our [documentation](https://docs.github.com/categories/github-pages-basics/) or [contact support](https://support.github.com/contact) and we’ll help you sort it out.
-->
