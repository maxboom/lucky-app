<?php

declare(strict_types=1);

namespace App\Domain\User\Entity;

use App\Domain\User\ValueObject\UserId;
use App\Domain\User\ValueObject\PhoneNumber;
use App\Domain\User\ValueObject\Username;
use App\Domain\User\ValueObject\AccessLink;

final class User
{
    public function __construct(
        private readonly UserId $id,
        private readonly Username $username,
        private readonly PhoneNumber $phoneNumber,
        private AccessLink $accessLink,
        private readonly \DateTimeImmutable $createdAt,
    ) {}

    public static function register(
        Username $username,
        PhoneNumber $phoneNumber,
    ): self {
        return new self(
            id: UserId::generate(),
            username: $username,
            phoneNumber: $phoneNumber,
            accessLink: AccessLink::generate(),
            createdAt: new \DateTimeImmutable(),
        );
    }

    public function regenerateLink(): void
    {
        $this->accessLink = AccessLink::generate();
    }

    public function deactivateLink(): void
    {
        $this->accessLink = $this->accessLink->deactivate();
    }

    public function id(): UserId
    {
        return $this->id;
    }

    public function username(): Username
    {
        return $this->username;
    }

    public function phoneNumber(): PhoneNumber
    {
        return $this->phoneNumber;
    }

    public function accessLink(): AccessLink
    {
        return $this->accessLink;
    }

    public function createdAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }
}
