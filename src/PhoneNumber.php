<?php


namespace Zing\LaravelSms;

use Zing\LaravelSms\Contracts\PhoneNumber as PhoneNumberContract;

class PhoneNumber implements PhoneNumberContract
{
    /**
     * @var int
     */
    protected $number;

    /**
     * @var int
     */
    protected $IDDCode;

    /**
     * PhoneNumberInterface constructor.
     *
     * @param int $numberWithoutIDDCode
     * @param string $IDDCode
     */
    public function __construct($numberWithoutIDDCode, $IDDCode = null)
    {
        $this->number = $numberWithoutIDDCode;
        $this->IDDCode = $IDDCode ? (int) ltrim($IDDCode, '+0') : null;
    }

    /**
     * 86.
     *
     * @return int
     */
    public function getIDDCode()
    {
        return $this->IDDCode;
    }

    /**
     * 18888888888.
     *
     * @return int
     */
    public function getNumber()
    {
        return $this->number;
    }

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

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getUniversalNumber();
    }

    public function jsonSerialize()
    {
        return $this->getUniversalNumber();
    }

}