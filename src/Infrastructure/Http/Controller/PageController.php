<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Controller;

use App\Application\Game\UseCase\GetGameHistory;
use App\Application\Game\UseCase\PlayGame;
use App\Application\User\UseCase\DeactivateLink;
use App\Application\User\UseCase\RegenerateLink;
use App\Domain\User\Entity\User;
use App\Domain\User\Repository\UserRepositoryInterface;
use App\Infrastructure\Container;

final class PageController
{
    private function loadUser(string $token): User
    {
        /** @var UserRepositoryInterface $repo */
        $repo = Container::getInstance()->get('userRepository');
        $user = $repo->findByToken($token);

        if ($user === null) {
            http_response_code(404);
            require __DIR__ . '/../../../UI/templates/error/404.php';
            exit;
        }

        if (!$user->accessLink()->isValid()) {
            http_response_code(410);
            require __DIR__ . '/../../../UI/templates/error/expired.php';
            exit;
        }

        return $user;
    }

    public function show(string $token): never
    {
        $user = $this->loadUser($token);
        require __DIR__ . '/../../../UI/templates/page/index.php';
        exit;
    }

    public function regenerate(string $token): never
    {
        $user = $this->loadUser($token);

        /** @var RegenerateLink $useCase */
        $useCase = Container::getInstance()->get(RegenerateLink::class);
        $user    = $useCase->execute($user);

        header('Location: /page/' . $user->accessLink()->token());
        exit;
    }

    public function deactivate(string $token): never
    {
        $user = $this->loadUser($token);

        /** @var DeactivateLink $useCase */
        $useCase = Container::getInstance()->get(DeactivateLink::class);
        $useCase->execute($user);

        $deactivated = true;
        require __DIR__ . '/../../../UI/templates/page/deactivated.php';
        exit;
    }

    public function play(string $token): never
    {
        $user = $this->loadUser($token);

        /** @var PlayGame $useCase */
        $useCase    = Container::getInstance()->get(PlayGame::class);
        $gameResult = $useCase->execute($user);

        require __DIR__ . '/../../../UI/templates/page/result.php';
        exit;
    }

    public function history(string $token): never
    {
        $user = $this->loadUser($token);

        /** @var GetGameHistory $useCase */
        $useCase = Container::getInstance()->get(GetGameHistory::class);
        $history = $useCase->execute($user);

        require __DIR__ . '/../../../UI/templates/page/history.php';
        exit;
    }
}
