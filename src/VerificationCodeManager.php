<?php

declare(strict_types=1);

namespace Zing\LaravelSms;

use Illuminate\Contracts\Cache\Repository;
use Zing\LaravelSms\Notifications\VerificationCode;

class VerificationCodeManager
{
    /**
     * @var \Illuminate\Contracts\Cache\Repository
     */
    protected $cacheManager;

    public function __construct(Repository $cacheManager)
    {
        $this->cacheManager = $cacheManager;
    }

    /**
     * @param \Zing\LaravelSms\SmsNumber|string $number
     */
    protected function getPrefixedKey($number): string
    {
        return config('sms.verification.prefix') . $number;
    }

    /**
     * @param \Zing\LaravelSms\SmsNumber|string $number
     * @param string|int $code
     */
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

    /**
     * @param \Zing\LaravelSms\SmsNumber|string $number
     * @param int|null $ttl
     */
    public function issue($number, $ttl = null): int
    {
        $length = config('sms.verification.length');
        $code = random_int((int) (10 ** ($length - 1)), (int) (10 ** $length) - 1);
        if ($ttl === null) {
            $ttl = config('sms.verification.ttl');
        }

        $key = $this->getPrefixedKey($number);
        if (! $number instanceof SmsNumber) {
            $number = new SmsNumber($number);
        }

        $number->notify(new VerificationCode((string) $code, $ttl));
        $this->cacheManager->set($key, $code, (int) $ttl * 60);

        return $code;
    }
}
