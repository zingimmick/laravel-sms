<?php

declare(strict_types=1);

namespace Zing\LaravelSms\Tests;

use Illuminate\Foundation\Testing\WithFaker;
use Zing\LaravelSms\Notifications\VerificationCode;
use Zing\LaravelSms\SmsNumber;
use Zing\LaravelSms\VerificationCodeManager;

class VerificationCodeManagerTest extends TestCase
{
    use WithFaker;

    protected $manager;

    protected function getEnvironmentSetUp($app): void
    {
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->manager = app(VerificationCodeManager::class);
    }

    public function testIssue(): void
    {
        $code = $this->manager->issue('18888888888');
        self::assertSame(config('sms.verification.length'), strlen((string) $code));
    }

    public function testVerify(): void
    {
        $code = $this->manager->issue(new SmsNumber('18888888888'));
        self::assertTrue($this->manager->verify(new SmsNumber('18888888888'), $code));
        self::assertFalse($this->manager->verify(new SmsNumber('18888888888'), $code + 1));
        config(['sms.verification.debug' => true]);
        self::assertTrue($this->manager->verify(new SmsNumber('18888888888'), $code + 1));
    }

    public function testVerification()
    {
        $code = $this->faker->numberBetween();
        $ttl = $this->faker->numberBetween();
        $verification = new VerificationCode($code, $ttl);
        self::assertSame(sprintf(config('sms.verification.content'), $code, $ttl), $verification->toSms());
    }
}
