<?php

declare(strict_types=1);

namespace App\Application\Game\UseCase;

use App\Domain\Game\Entity\GameResult;
use App\Domain\Game\Repository\GameResultRepositoryInterface;
use App\Domain\User\Entity\User;

final class PlayGame
{
    public function __construct(
        private readonly GameResultRepositoryInterface $results,
    ) {}

    public function execute(User $user): GameResult
    {
        $result = GameResult::play($user->id());
        $this->results->save($result);

        return $result;
    }
}
