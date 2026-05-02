<?php

declare(strict_types=1);

namespace App\Domain\Game\Entity;

use App\Domain\Game\ValueObject\GameResultId;
use App\Domain\User\ValueObject\UserId;
use App\Domain\Game\ValueObject\GameOutcome;

final class GameResult
{
    public function __construct(
        private readonly GameResultId $id,
        private readonly UserId $userId,
        private readonly int $rolledNumber,
        private readonly GameOutcome $outcome,
        private readonly float $winAmount,
        private readonly \DateTimeImmutable $playedAt,
    ) {}

    public static function play(UserId $userId): self
    {
        $number = random_int(1, 1000);
        $isWin = $number % 2 === 0;
        $winAmount = $isWin ? self::calculateWinAmount($number) : 0.0;

        return new self(
            id: GameResultId::generate(),
            userId: $userId,
            rolledNumber: $number,
            outcome: $isWin ? GameOutcome::Win : GameOutcome::Lose,
            winAmount: $winAmount,
            playedAt: new \DateTimeImmutable(),
        );
    }

    private static function calculateWinAmount(int $number): float
    {
        $percent = match (true) {
            $number > 900 => 0.70,
            $number > 600 => 0.50,
            $number > 300 => 0.30,
            default       => 0.10,
        };

        return round($number * $percent, 2);
    }

    public function id(): GameResultId
    {
        return $this->id;
    }

    public function userId(): UserId
    {
        return $this->userId;
    }

    public function rolledNumber(): int
    {
        return $this->rolledNumber;
    }

    public function outcome(): GameOutcome
    {
        return $this->outcome;
    }

    public function winAmount(): float
    {
        return $this->winAmount;
    }

    public function playedAt(): \DateTimeImmutable
    {
        return $this->playedAt;
    }
}
