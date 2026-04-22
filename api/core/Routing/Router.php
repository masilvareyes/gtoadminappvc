<?php

declare(strict_types=1);

namespace App\Core\Routing;

use App\Core\Http\Request;
use App\Core\Http\Response;

final class Router
{
    /** @var list<Route> */
    private array $routes = [];

    /**
     * @param callable(\App\Core\Http\Request): \App\Core\Http\Response $handler
     */
    public function get(string $path, callable $handler): void
    {
        $this->addRoute('GET', $path, $handler);
    }

    /**
     * @param callable(\App\Core\Http\Request): \App\Core\Http\Response $handler
     */
    public function post(string $path, callable $handler): void
    {
        $this->addRoute('POST', $path, $handler);
    }

    /**
     * @param callable(\App\Core\Http\Request): \App\Core\Http\Response $handler
     */
    private function addRoute(string $method, string $path, callable $handler): void
    {
        $normalized = Request::normalizePath($path);
        $this->routes[] = new Route($method, $normalized, $handler);
    }

    public function dispatch(Request $request): Response
    {
        foreach ($this->routes as $route) {
            if ($route->method !== $request->method) {
                continue;
            }
            if ($route->pattern !== $request->path) {
                continue;
            }

            return $route->run($request);
        }

        return Response::jsonError(
            'NOT_FOUND',
            'Ruta no encontrada',
            ['path' => $request->path],
            404
        );
    }
}
