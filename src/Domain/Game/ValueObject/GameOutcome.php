<?php

declare(strict_types=1);

namespace App\Domain\Game\ValueObject;

enum GameOutcome: string
{
    case Win  = 'win';
    case Lose = 'lose';
}
