<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\MySQL;

use App\Domain\User\Entity\User;
use App\Domain\User\Repository\UserRepositoryInterface;
use App\Domain\User\ValueObject\AccessLink;
use App\Domain\User\ValueObject\PhoneNumber;
use App\Domain\User\ValueObject\UserId;
use App\Domain\User\ValueObject\Username;

final class MySQLUserRepository implements UserRepositoryInterface
{
    public function __construct(private readonly \PDO $pdo) {}

    public function save(User $user): void
    {
        $stmt = $this->pdo->prepare('
            INSERT INTO users (id, username, phone_number, link_token, link_expires_at, link_active, created_at)
            VALUES (:id, :username, :phone, :token, :expires_at, :active, :created_at)
            ON DUPLICATE KEY UPDATE
                link_token    = :token,
                link_expires_at = :expires_at,
                link_active   = :active
        ');

        $stmt->execute([
            'id'         => $user->id()->toString(),
            'username'   => $user->username()->toString(),
            'phone'      => $user->phoneNumber()->toString(),
            'token'      => $user->accessLink()->token(),
            'expires_at' => $user->accessLink()->expiresAt()->format('Y-m-d H:i:s'),
            'active'     => $user->accessLink()->isActive() ? 1 : 0,
            'created_at' => $user->createdAt()->format('Y-m-d H:i:s'),
        ]);
    }

    public function findById(UserId $id): ?User
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE id = :id');
        $stmt->execute(['id' => $id->toString()]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $row ? $this->hydrate($row) : null;
    }

    public function findByToken(string $token): ?User
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE link_token = :token');
        $stmt->execute(['token' => $token]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $row ? $this->hydrate($row) : null;
    }

    private function hydrate(array $row): User
    {
        $link = AccessLink::restore(
            token: $row['link_token'],
            expiresAt: new \DateTimeImmutable($row['link_expires_at']),
            active: (bool) $row['link_active'],
        );

        $reflection = new \ReflectionClass(User::class);
        $user = $reflection->newInstanceWithoutConstructor();

        $set = function (string $prop, mixed $value) use ($user, $reflection): void {
            $p = $reflection->getProperty($prop);
            $p->setValue($user, $value);
        };

        $set('id', UserId::fromString($row['id']));
        $set('username', Username::fromString($row['username']));
        $set('phoneNumber', PhoneNumber::fromString($row['phone_number']));
        $set('accessLink', $link);
        $set('createdAt', new \DateTimeImmutable($row['created_at']));

        return $user;
    }
}
