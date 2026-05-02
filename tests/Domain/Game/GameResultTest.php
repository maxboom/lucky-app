<?php

declare(strict_types=1);

namespace App\Tests\Domain\Game;

use App\Domain\Game\Entity\GameResult;
use App\Domain\Game\ValueObject\GameOutcome;
use App\Domain\Game\ValueObject\GameResultId;
use App\Domain\User\ValueObject\UserId;
use PHPUnit\Framework\TestCase;

final class GameResultTest extends TestCase
{
    public function testPlayProducesResultWithNumberInRange(): void
    {
        $userId = UserId::generate();
        $result = GameResult::play($userId);

        $this->assertGreaterThanOrEqual(1, $result->rolledNumber());
        $this->assertLessThanOrEqual(1000, $result->rolledNumber());
        $this->assertTrue($result->userId()->equals($userId));
    }

    public function testEvenNumberIsWin(): void
    {
        $gotWin = false;
        for ($i = 0; $i < 200; $i++) {
            $result = GameResult::play(UserId::generate());
            if ($result->rolledNumber() % 2 === 0) {
                $this->assertSame(GameOutcome::Win, $result->outcome());
                $this->assertGreaterThan(0, $result->winAmount());
                $gotWin = true;
                break;
            }
        }
        $this->assertTrue($gotWin, 'Expected at least one even number in 200 rolls.');
    }

    public function testOddNumberIsLose(): void
    {
        $gotLose = false;
        for ($i = 0; $i < 200; $i++) {
            $result = GameResult::play(UserId::generate());
            if ($result->rolledNumber() % 2 !== 0) {
                $this->assertSame(GameOutcome::Lose, $result->outcome());
                $this->assertSame(0.0, $result->winAmount());
                $gotLose = true;
                break;
            }
        }
        $this->assertTrue($gotLose, 'Expected at least one odd number in 200 rolls.');
    }

    /** @dataProvider winAmountProvider */
    public function testWinAmountCalculation(int $number, float $expectedPercent): void
    {
        if ($number % 2 !== 0) {
            $this->markTestSkipped('Not testing odd numbers here.');
        }

        $expected = round($number * $expectedPercent, 2);

        $result = $this->makeResultWithNumber($number);

        $this->assertSame($expected, $result->winAmount());
    }

    public static function winAmountProvider(): array
    {
        return [
            'above 900'         => [950, 0.70],
            'above 600'         => [700, 0.50],
            'above 300'         => [400, 0.30],
            'at 300 or below'   => [200, 0.10],
            'exactly 300'       => [300, 0.10],
        ];
    }

    private function makeResultWithNumber(int $number): GameResult
    {
        $reflection = new \ReflectionClass(GameResult::class);
        $result     = $reflection->newInstanceWithoutConstructor();

        $isWin     = $number % 2 === 0;
        $outcome   = $isWin ? GameOutcome::Win : GameOutcome::Lose;
        $percent   = match (true) {
            $number > 900 => 0.70,
            $number > 600 => 0.50,
            $number > 300 => 0.30,
            default       => 0.10,
        };
        $winAmount = $isWin ? round($number * $percent, 2) : 0.0;

        foreach (['id' => GameResultId::generate(),
                  'userId' => UserId::generate(),
                  'rolledNumber' => $number,
                  'outcome' => $outcome,
                  'winAmount' => $winAmount,
                  'playedAt' => new \DateTimeImmutable()] as $prop => $value) {
            $p = $reflection->getProperty($prop);
            $p->setValue($result, $value);
        }

        return $result;
    }
}
