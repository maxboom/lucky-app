<?php

declare(strict_types=1);

namespace App\Application\User\UseCase;

use App\Domain\User\Entity\User;
use App\Domain\User\Repository\UserRepositoryInterface;
use App\Domain\User\ValueObject\PhoneNumber;
use App\Domain\User\ValueObject\Username;

final class RegisterUser
{
    public function __construct(
        private readonly UserRepositoryInterface $users,
    ) {}

    public function execute(string $username, string $phoneNumber): User
    {
        $user = User::register(
            username: Username::fromString($username),
            phoneNumber: PhoneNumber::fromString($phoneNumber),
        );

        $this->users->save($user);

        return $user;
    }
}
