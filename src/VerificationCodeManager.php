<?php

declare(strict_types=1);

namespace Zing\LaravelSms;

use Illuminate\Contracts\Cache\Repository;
use Zing\LaravelSms\Notifications\VerificationCode;

class VerificationCodeManager
{
    protected $cacheManager;

    /**
     * VerificationCodeManager constructor.
     *
     * @param $cacheManager
     */
    public function __construct(Repository $cacheManager)
    {
        $this->cacheManager = $cacheManager;
    }

    protected function getPrefixedKey($number): string
    {
        return config('sms.verification.prefix') . $number;
    }

    public function verify($number, $code): bool
    {
        if (config('sms.verification.debug', false)) {
            return true;
        }

        $issuedCode = $this->cacheManager->get($this->getPrefixedKey($number));
        if (! $issuedCode) {
            return false;
        }

        return (int) $code === (int) $issuedCode;
    }

    public function issue($number, $ttl = null): int
    {
        $length = config('sms.verification.length');
        $code = random_int(10 ** ($length - 1), (10 ** $length) - 1);
        if ($ttl === null) {
            $ttl = config('sms.verification.ttl');
        }

        $this->cacheManager->set($this->getPrefixedKey($number), $code, $ttl * 60);
        if (! $number instanceof SmsNumber) {
            $number = new SmsNumber($number);
        }

        $number->notify(new VerificationCode($code, $ttl));

        return $code;
    }
}
