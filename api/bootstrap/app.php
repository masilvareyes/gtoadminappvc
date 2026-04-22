<?php

declare(strict_types=1);

use App\Core\Http\Request;
use App\Core\Http\Response;
use App\Core\Routing\Router;

require_once __DIR__ . '/env.php';
require_once __DIR__ . '/providers.php';

return static function (): void {
    $root = dirname(__DIR__, 2);
    load_env($root . DIRECTORY_SEPARATOR . '.env');

    register_providers();

    $router = new Router();
    $registerRoutes = require $root . '/api/routes/api.php';
    $registerRoutes($router);

    $basePath = env_string('APP_BASE_PATH', null);
    $request = Request::fromGlobals($basePath);

    try {
        $response = $router->dispatch($request);
    } catch (\Throwable $e) {
        $debug = env_bool('APP_DEBUG', false);
        $message = $debug
            ? $e->getMessage()
            : 'Error interno del servidor';

        $response = Response::jsonError(
            'INTERNAL_ERROR',
            $message,
            $debug ? ['trace' => $e->getTraceAsString()] : null,
            500
        );
    }

    $response->send();
};