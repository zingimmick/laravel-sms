<?php

namespace Zing\LaravelSms\Tests;

use Zing\LaravelSms\PhoneNumber;

class PhoneNumberTest extends TestCase
{
    public function test_only_number()
    {
        $n = new PhoneNumber(18888888888);
        $this->assertSame(18888888888, $n->getNumber());
        $this->assertNull($n->getIDDCode());
        $this->assertSame('18888888888', $n->getUniversalNumber());
        $this->assertSame('18888888888', $n->getZeroPrefixedNumber());
        $this->assertSame('18888888888', (string) $n);
    }

    public function test_diff_code()
    {
        $n = new PhoneNumber(18888888888, 68);
        $this->assertSame(68, $n->getIDDCode());

        $n = new PhoneNumber(18888888888, '+68');
        $this->assertSame(68, $n->getIDDCode());

        $n = new PhoneNumber(18888888888, '0068');
        $this->assertSame(68, $n->getIDDCode());
    }

    public function test_json_encode()
    {
        $n = new PhoneNumber(18888888888, 68);
        $this->assertSame(json_encode(['number' => $n->getUniversalNumber()]), json_encode(['number' => $n]));
    }

    public function test_prefixed_number()
    {
        $n = new PhoneNumber(18888888888, 86);
        $this->assertSame(18888888888, $n->getNumber());
        $this->assertSame(86, $n->getIDDCode());
        $this->assertSame('+8618888888888', $n->getUniversalNumber());
        $this->assertSame('008618888888888', $n->getZeroPrefixedNumber());
        $this->assertSame('aa8618888888888', $n->getPrefixedNumber('aa'));
        $this->assertSame('+8618888888888', (string) $n);
    }
}
