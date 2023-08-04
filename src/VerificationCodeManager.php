<?php

declare(strict_types=1);

namespace Zing\LaravelSms;

use Illuminate\Contracts\Cache\Repository;
use Zing\LaravelSms\Notifications\VerificationCode;

class VerificationCodeManager
{
    public function __construct(
        protected Repository $cacheRepository
    ) {
    }

    protected function getPrefixedKey(SmsNumber|string $number): string
    {
        return config('sms.verification.prefix') . $number;
    }

    public function verify(SmsNumber|string $number, int|string $code): bool
    {
        if (config('sms.verification.debug', false)) {
            return true;
        }

        $issuedCode = $this->cacheRepository->get($this->getPrefixedKey($number));
        if (! $issuedCode) {
            return false;
        }

        return (int) $code === (int) $issuedCode;
    }

    public function issue(SmsNumber|string $number, mixed $ttl = null): int
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
        $this->cacheRepository->set($key, $code, (int) $ttl * 60);

        return $code;
    }
}
