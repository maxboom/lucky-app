<?php

declare(strict_types=1);

namespace App\Domain\Game\Repository;

use App\Domain\Game\Entity\GameResult;
use App\Domain\User\ValueObject\UserId;

interface GameResultRepositoryInterface
{
    public function save(GameResult $result): void;

    /** @return GameResult[] */
    public function findLastByUser(UserId $userId, int $limit): array;
}
