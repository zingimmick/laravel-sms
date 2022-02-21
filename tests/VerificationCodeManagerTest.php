<?php

declare(strict_types=1);

namespace Zing\LaravelSms\Tests;

use Illuminate\Foundation\Testing\WithFaker;
use Zing\LaravelSms\Notifications\VerificationCode;
use Zing\LaravelSms\SmsNumber;
use Zing\LaravelSms\VerificationCodeManager;

/**
 * @internal
 */
final class VerificationCodeManagerTest extends TestCase
{
    use WithFaker;

    /**
     * @var \Zing\LaravelSms\VerificationCodeManager
     */
    private $verificationCodeManager;

    protected function getEnvironmentSetUp($app): void
    {
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->verificationCodeManager = app(VerificationCodeManager::class);
    }

    public function testIssue(): void
    {
        $code = $this->verificationCodeManager->issue('18888888888');
        self::assertSame(config('sms.verification.length'), \strlen((string) $code));
        $code = $this->verificationCodeManager->issue(new SmsNumber('18888888888'));
        self::assertSame(config('sms.verification.length'), \strlen((string) $code));
    }

    public function testVerify(): void
    {
        self::assertFalse($this->verificationCodeManager->verify(new SmsNumber('18888888888'), ''));
        $code = $this->verificationCodeManager->issue(new SmsNumber('18888888888'));
        self::assertTrue($this->verificationCodeManager->verify(new SmsNumber('18888888888'), $code));
        self::assertFalse($this->verificationCodeManager->verify(new SmsNumber('18888888888'), $code + 1));
        config([
            'sms.verification.debug' => true,
        ]);
        self::assertTrue($this->verificationCodeManager->verify(new SmsNumber('18888888888'), $code + 1));
    }

    public function testVerification(): void
    {
        $code = $this->faker->numberBetween();
        $ttl = $this->faker->numberBetween();
        $verificationCode = new VerificationCode((string) $code, $ttl);
        self::assertSame(sprintf(config('sms.verification.content'), $code, $ttl), $verificationCode->toSms());
    }
}
