<?php

declare(strict_types=1);

namespace App\Infrastructure;

use App\Application\Game\UseCase\GetGameHistory;
use App\Application\Game\UseCase\PlayGame;
use App\Application\User\UseCase\DeactivateLink;
use App\Application\User\UseCase\RegenerateLink;
use App\Application\User\UseCase\RegisterUser;
use App\Infrastructure\Persistence\MySQL\MySQLGameResultRepository;
use App\Infrastructure\Persistence\MySQL\MySQLUserRepository;

final class Container
{
    private static ?self $instance = null;
    private array $bindings = [];

    private function __construct() {}

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
            self::$instance->boot();
        }
        return self::$instance;
    }

    private function boot(): void
    {
        $pdo = new \PDO(
            dsn: sprintf(
                'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
                $_ENV['DB_HOST'] ?? 'db',
                $_ENV['DB_PORT'] ?? '3306',
                $_ENV['DB_NAME'] ?? 'lucky_app',
            ),
            username: $_ENV['DB_USER'] ?? 'lucky',
            password: $_ENV['DB_PASS'] ?? 'lucky',
            options: [
                \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            ],
        );

        $userRepo        = new MySQLUserRepository($pdo);
        $gameResultRepo  = new MySQLGameResultRepository($pdo);

        $this->bindings[RegisterUser::class]  = new RegisterUser($userRepo);
        $this->bindings[RegenerateLink::class] = new RegenerateLink($userRepo);
        $this->bindings[DeactivateLink::class] = new DeactivateLink($userRepo);
        $this->bindings[PlayGame::class]       = new PlayGame($gameResultRepo);
        $this->bindings[GetGameHistory::class] = new GetGameHistory($gameResultRepo);

        $this->bindings['userRepository']      = $userRepo;
        $this->bindings['gameResultRepository'] = $gameResultRepo;
    }

    public function get(string $id): mixed
    {
        if (!isset($this->bindings[$id])) {
            throw new \RuntimeException("Service not found: {$id}");
        }
        return $this->bindings[$id];
    }
}
