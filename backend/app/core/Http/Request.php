<?php

declare(strict_types=1);

namespace App\Core\Http;

final class Request
{
    public function __construct(
        public readonly string $method,
        public readonly string $path
    ) {
    }

    public static function fromGlobals(?string $basePath = null): self
    {
        $method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        if (($q = strpos($uri, '?')) !== false) {
            $uri = substr($uri, 0, $q);
        }
        $path = self::normalizePath((string) $uri);
        $path = self::stripBasePath($path, $basePath);

        return new self($method, $path);
    }

    /**
     * Normaliza path: asegura que empiece por / y sin duplicar barras.
     */
    public static function normalizePath(string $raw): string
    {
        $path = '/' . ltrim($raw, '/');
        if ($path !== '/' && str_ends_with($path, '/')) {
            $path = rtrim($path, '/');
        }
        return $path === '' ? '/' : $path;
    }

    /**
     * Remueve el prefijo de base path (p. ej. /gtosVC2ok) del path real.
     * Si el request es exactamente el base path, regresa "/".
     */
    private static function stripBasePath(string $path, ?string $basePath): string
    {
        if ($basePath === null) {
            return $path;
        }

        $base = trim($basePath);
        if ($base === '') {
            return $path;
        }

        $base = self::normalizePath($base);
        if ($base === '/') {
            return $path;
        }

        if ($path === $base) {
            return '/';
        }

        $prefix = $base . '/';
        if (str_starts_with($path, $prefix)) {
            $rest = substr($path, strlen($base));
            return self::normalizePath($rest);
        }

        return $path;
    }
}
