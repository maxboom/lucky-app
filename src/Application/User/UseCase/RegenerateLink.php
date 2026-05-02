<?php

declare(strict_types=1);

namespace App\Application\User\UseCase;

use App\Domain\User\Entity\User;
use App\Domain\User\Repository\UserRepositoryInterface;

final class RegenerateLink
{
    public function __construct(
        private readonly UserRepositoryInterface $users,
    ) {}

    public function execute(User $user): User
    {
        $user->regenerateLink();
        $this->users->save($user);

        return $user;
    }
}
