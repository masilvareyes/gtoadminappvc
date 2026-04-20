<?php

declare(strict_types=1);

use App\Core\Database\Connection;
use App\Core\Database\QueryBuilder;
use App\Core\Http\Request;
use App\Core\Http\Response;
use App\Core\Routing\Router;

function register_db_health_routes(Router $router): void
{
    $router->get('/health/db', static function (Request $request): Response {
        try {
            $queryBuilder = new QueryBuilder(Connection::getInstance());
            $result = $queryBuilder->fetchOne('SELECT 1 AS db_ok');

            return Response::jsonSuccess([
                'status' => 'ok',
                'database' => 'connected',
                'probe' => $result,
                'time' => gmdate('c'),
            ]);
        } catch (\Throwable $e) {
            $debug = env_bool('APP_DEBUG', false);

            return Response::jsonError(
                'DB_CONNECTION_ERROR',
                'No fue posible conectar con la base de datos.',
                $debug ? ['exception' => $e->getMessage()] : null,
                500
            );
        }
    });
}
