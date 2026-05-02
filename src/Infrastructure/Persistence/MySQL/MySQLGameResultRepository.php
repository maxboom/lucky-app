<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\MySQL;

use App\Domain\Game\Entity\GameResult;
use App\Domain\Game\Repository\GameResultRepositoryInterface;
use App\Domain\Game\ValueObject\GameOutcome;
use App\Domain\Game\ValueObject\GameResultId;
use App\Domain\User\ValueObject\UserId;

final class MySQLGameResultRepository implements GameResultRepositoryInterface
{
    public function __construct(private readonly \PDO $pdo) {}

    public function save(GameResult $result): void
    {
        $stmt = $this->pdo->prepare('
            INSERT INTO game_results (id, user_id, rolled_number, outcome, win_amount, played_at)
            VALUES (:id, :user_id, :number, :outcome, :win_amount, :played_at)
        ');

        $stmt->execute([
            'id'         => $result->id()->toString(),
            'user_id'    => $result->userId()->toString(),
            'number'     => $result->rolledNumber(),
            'outcome'    => $result->outcome()->value,
            'win_amount' => $result->winAmount(),
            'played_at'  => $result->playedAt()->format('Y-m-d H:i:s'),
        ]);
    }

    public function findLastByUser(UserId $userId, int $limit): array
    {
        $stmt = $this->pdo->prepare('
            SELECT * FROM game_results
            WHERE user_id = :user_id
            ORDER BY played_at DESC
            LIMIT :limit
        ');

        $stmt->bindValue('user_id', $userId->toString());
        $stmt->bindValue('limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();

        return array_map(
            fn(array $row) => $this->hydrate($row),
            $stmt->fetchAll(\PDO::FETCH_ASSOC)
        );
    }

    private function hydrate(array $row): GameResult
    {
        $reflection = new \ReflectionClass(GameResult::class);
        $result = $reflection->newInstanceWithoutConstructor();

        $set = function (string $prop, mixed $value) use ($result, $reflection): void {
            $p = $reflection->getProperty($prop);
            $p->setValue($result, $value);
        };

        $set('id', GameResultId::fromString($row['id']));
        $set('userId', UserId::fromString($row['user_id']));
        $set('rolledNumber', (int) $row['rolled_number']);
        $set('outcome', GameOutcome::from($row['outcome']));
        $set('winAmount', (float) $row['win_amount']);
        $set('playedAt', new \DateTimeImmutable($row['played_at']));

        return $result;
    }
}
