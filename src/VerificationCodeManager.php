<?php

declare(strict_types=1);

namespace Zing\LaravelSms;

use Illuminate\Contracts\Cache\Factory;

class VerificationCodeManager
{
    protected $cacheManager;

    /**
     * VerificationCodeManager constructor.
     *
     * @param $cacheManager
     */
    public function __construct(Factory $cacheManager)
    {
        $this->cacheManager = $cacheManager;
    }

    protected function getPrefixedKey($number)
    {
        return config('sms.verification.prefix') . $number;
    }

    public function verify($number, $code)
    {
        return $code === $this->cacheManager->get($this->getPrefixedKey($number));
    }

    public function issue($number)
    {
        $length = config('sms.verification.length');
        $code = random_int(10 ** ($length - 1), (10 ** $length) - 1);
        $this->cacheManager->set($this->getPrefixedKey($number), $code, 600);

        return $code;
    }
}
