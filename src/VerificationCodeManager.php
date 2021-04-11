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

    /**
     * VerificationCodeManager constructor.
     *
     * @param \Illuminate\Contracts\Cache\Repository $cacheManager
     */
    public function __construct(Repository $cacheManager)
    {
        $this->cacheManager = $cacheManager;
    }

    /**
     * @param \Overtrue\EasySms\Contracts\PhoneNumberInterface|string $number
     * @return string
     */
    protected function getPrefixedKey($number): string
    {
        return config('sms.verification.prefix') . $number;
    }

    /**
     * @param \Overtrue\EasySms\Contracts\PhoneNumberInterface|string $number
     * @param int|string $code
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return bool
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
     * @param \Overtrue\EasySms\Contracts\PhoneNumberInterface|string|int $number
     * @param int|null $ttl
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return int
     */
    public function issue($number, $ttl = null): int
    {
        $length = config('sms.verification.length');
        $code = random_int(10 ** ($length - 1), (10 ** $length) - 1);
        if ($ttl === null) {
            $ttl = (int) config('sms.verification.ttl');
        }

        $this->cacheManager->set($this->getPrefixedKey($number), $code, $ttl * 60);
        if (! $number instanceof SmsNumber) {
            $number = new SmsNumber($number);
        }

        $number->notify(new VerificationCode($code, $ttl));

        return $code;
    }
}
