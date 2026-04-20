<?php

declare(strict_types=1);

use App\Core\Http\Request;
use App\Core\Http\Response;
use App\Core\Routing\Router;

require_once __DIR__ . '/health.php';
require_once __DIR__ . '/db_health.php';

return static function (Router $router): void {
    register_health_routes($router);
    register_db_health_routes($router);

    if (env_bool('GTV_ALLOW_EXCEPTION_TEST', false)) {
        $router->get('/api/debug/exception', static function (Request $request): Response {
            throw new \RuntimeException('Excepción técnica de prueba (GTV_ALLOW_EXCEPTION_TEST)');
        });
    }
};
