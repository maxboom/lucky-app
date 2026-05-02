<?php

declare(strict_types=1);

namespace App\Application\Game\UseCase;

use App\Domain\Game\Repository\GameResultRepositoryInterface;
use App\Domain\User\Entity\User;

final class GetGameHistory
{
    private const HISTORY_LIMIT = 3;

    public function __construct(
        private readonly GameResultRepositoryInterface $results,
    ) {}

    public function execute(User $user): array
    {
        return $this->results->findLastByUser($user->id(), self::HISTORY_LIMIT);
    }
}
