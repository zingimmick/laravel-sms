<?php

declare(strict_types=1);

namespace Zing\LaravelSms\Tests;

use Zing\LaravelSms\Notifications\VerificationCode;
use Zing\LaravelSms\SmsNumber;
use Zing\LaravelSms\VerificationCodeManager;
use function Pest\Faker\faker;
use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertSame;
use function PHPUnit\Framework\assertTrue;

uses(DefaultConfigTestCase::class);
beforeEach(
    function (): void {
        $this->manager = app(VerificationCodeManager::class);
    }
);
it(
    'issue',
    function (): void {
        $code = $this->manager->issue('18888888888');
        assertSame(config('sms.verification.length'), strlen((string) $code));
    }
);
//it(
//    'verification',
//    function (): void {
//        $code = faker()->numberBetween();
//        $ttl = faker()->numberBetween();
//        $verificationCode = new VerificationCode($code, $ttl);
//        assertSame(sprintf(config('sms.verification.content'), $code, $ttl), $verificationCode->toSms());
//    }
//);
it(
    'verify',
    function (): void {
        $code = $this->manager->issue(new SmsNumber('18888888888'));
        assertTrue($this->manager->verify(new SmsNumber('18888888888'), $code));
        assertFalse($this->manager->verify(new SmsNumber('18888888888'), $code + 1));
        config(
            [
                'sms.verification.debug' => true,
            ]
        );
        assertTrue($this->manager->verify(new SmsNumber('18888888888'), $code + 1));
    }
);
