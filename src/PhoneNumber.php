<?php

namespace Zing\LaravelSms;

class PhoneNumber extends \Overtrue\EasySms\PhoneNumber
{
    /**
     * +8618888888888.
     *
     * @return string
     */
    public function getUniversalNumber()
    {
        return $this->getPrefixedNumber('+');
    }

    /**
     * 008618888888888.
     *
     * @return string
     */
    public function getZeroPrefixedNumber()
    {
        return $this->getPrefixedNumber('00');
    }

    public function getPrefixedNumber($prefix)
    {
        return $this->getPrefixedIDDCode($prefix) . $this->number;
    }

    /**
     * @param string $prefix
     *
     * @return string|null
     */
    public function getPrefixedIDDCode($prefix)
    {
        return $this->IDDCode ? $prefix . $this->IDDCode : null;
    }
}
