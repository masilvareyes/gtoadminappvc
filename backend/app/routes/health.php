<?php

declare(strict_types=1);

use App\Core\Http\Request;
use App\Core\Http\Response;
use App\Core\Routing\Router;

function register_health_routes(Router $router): void
{
    $router->get('/health', static function (Request $request): Response {
        return Response::jsonSuccess([
            'status' => 'ok',
            'service' => env_string('APP_NAME', 'gtosvc-api'),
            'time' => gmdate('c'),
        ]);
    });
}
