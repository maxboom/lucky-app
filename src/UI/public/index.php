<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../vendor/autoload.php';

use App\Infrastructure\Http\Controller\HomeController;
use App\Infrastructure\Http\Controller\PageController;

$envFile = __DIR__ . '/../../../.env';
if (file_exists($envFile)) {
    foreach (parse_ini_file($envFile) as $key => $value) {
        $_ENV[$key] = $value;
    }
}

$method = $_SERVER['REQUEST_METHOD'];
$uri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri    = rtrim($uri, '/') ?: '/';

try {
    match (true) {
        $uri === '/' && $method === 'GET'
            => (new HomeController())->index(),

        $uri === '/register' && $method === 'POST'
            => (new HomeController())->register(),

        preg_match('#^/page/([a-f0-9]{64})$#', $uri, $m) && $method === 'GET'
            => (new PageController())->show($m[1]),

        preg_match('#^/page/([a-f0-9]{64})/regenerate$#', $uri, $m) && $method === 'POST'
            => (new PageController())->regenerate($m[1]),

        preg_match('#^/page/([a-f0-9]{64})/deactivate$#', $uri, $m) && $method === 'POST'
            => (new PageController())->deactivate($m[1]),

        preg_match('#^/page/([a-f0-9]{64})/play$#', $uri, $m) && $method === 'POST'
            => (new PageController())->play($m[1]),

        preg_match('#^/page/([a-f0-9]{64})/history$#', $uri, $m) && $method === 'GET'
            => (new PageController())->history($m[1]),

        default => (static function (): never {
            http_response_code(404);
            require __DIR__ . '/../../templates/error/404.php';
            exit;
        })(),
    };
} catch (\Throwable $e) {
    http_response_code(500);
    error_log($e->getMessage() . "\n" . $e->getTraceAsString());
    echo '<h1>500 Internal Server Error</h1>';
    if ($_ENV['APP_ENV'] === 'dev') {
        echo '<pre>' . htmlspecialchars($e->getMessage()) . "\n" . htmlspecialchars($e->getTraceAsString()) . '</pre>';
    }
}
