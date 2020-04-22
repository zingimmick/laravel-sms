<?php

namespace Zing\LaravelSms\Contracts;

use JsonSerializable;
use Overtrue\EasySms\Contracts\PhoneNumberInterface;

interface PhoneNumber extends JsonSerializable, PhoneNumberInterface
{
    /**
     * 86.
     *
     * @return int
     */
    public function getIDDCode();

    /**
     * 18888888888.
     *
     * @return int
     */
    public function getNumber();

    /**
     * +8618888888888.
     *
     * @return string
     */
    public function getUniversalNumber();

    /**
     * 008618888888888.
     *
     * @return string
     */
    public function getZeroPrefixedNumber();

    /**
     * @return string
     */
    public function __toString();
}
