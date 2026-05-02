<?php

declare(strict_types=1);

namespace App\Tests\Domain\User;

use App\Domain\User\Entity\User;
use App\Domain\User\ValueObject\PhoneNumber;
use App\Domain\User\ValueObject\Username;
use PHPUnit\Framework\TestCase;

final class UserTest extends TestCase
{
    public function testRegisterCreatesUserWithValidLink(): void
    {
        $user = User::register(
            Username::fromString('john_doe'),
            PhoneNumber::fromString('380991234567'),
        );

        $this->assertSame('john_doe', $user->username()->toString());
        $this->assertTrue($user->accessLink()->isValid());
        $this->assertTrue($user->accessLink()->isActive());
        $this->assertStringMatchesFormat('%x', $user->accessLink()->token());
    }

    public function testRegenerateLinkChangesToken(): void
    {
        $user  = User::register(Username::fromString('jane'), PhoneNumber::fromString('1234567'));
        $token = $user->accessLink()->token();

        $user->regenerateLink();

        $this->assertNotSame($token, $user->accessLink()->token());
        $this->assertTrue($user->accessLink()->isValid());
    }

    public function testDeactivateLinkMakesItInvalid(): void
    {
        $user = User::register(Username::fromString('jane'), PhoneNumber::fromString('1234567'));

        $user->deactivateLink();

        $this->assertFalse($user->accessLink()->isValid());
        $this->assertFalse($user->accessLink()->isActive());
    }

    public function testUsernameCannotBeEmpty(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        Username::fromString('   ');
    }

    public function testPhoneNumberRejectsShortInput(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        PhoneNumber::fromString('123');
    }
}
