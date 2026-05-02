<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Controller;

use App\Application\User\UseCase\RegisterUser;
use App\Infrastructure\Container;

final class HomeController
{
    public function index(): never
    {
        require __DIR__ . '/../../../UI/templates/home/index.php';
        exit;
    }

    public function register(): never
    {
        $username    = trim($_POST['username'] ?? '');
        $phoneNumber = trim($_POST['phone_number'] ?? '');

        $errors = [];

        try {
            /** @var RegisterUser $useCase */
            $useCase = Container::getInstance()->get(RegisterUser::class);
            $user    = $useCase->execute($username, $phoneNumber);

            header('Location: /page/' . $user->accessLink()->token());
            exit;
        } catch (\InvalidArgumentException $e) {
            $errors[] = $e->getMessage();
        } catch (\Throwable $e) {
            $errors[] = 'Registration failed. Please try again.';
            error_log($e->getMessage());
        }

        require __DIR__ . '/../../../UI/templates/home/index.php';
        exit;
    }
}
