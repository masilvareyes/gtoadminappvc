<?php

declare(strict_types=1);

/**
 * Carga variables de entorno desde .env (formato KEY=valor, líneas vacías y # comentarios).
 */
function load_env(string $envFilePath): void
{
    if (!is_readable($envFilePath)) {
        return;
    }

    $lines = file($envFilePath, FILE_IGNORE_NEW_LINES) ?: [];
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#')) {
            continue;
        }
        if (!str_contains($line, '=')) {
            continue;
        }
        [$name, $value] = explode('=', $line, 2);
        $name = trim($name);
        if ($name === '') {
            continue;
        }
        $value = trim($value);
        if (
            (str_starts_with($value, '"') && str_ends_with($value, '"'))
            || (str_starts_with($value, "'") && str_ends_with($value, "'"))
        ) {
            $value = substr($value, 1, -1);
        }

        if (!array_key_exists($name, $_ENV)) {
            $_ENV[$name] = $value;
        }
        if (!array_key_exists($name, $_SERVER)) {
            $_SERVER[$name] = $value;
        }
        putenv(sprintf('%s=%s', $name, $value));
    }
}

function env_string(string $key, ?string $default = null): ?string
{
    $v = $_ENV[$key] ?? $_SERVER[$key] ?? getenv($key);
    if ($v === false || $v === null || $v === '') {
        return $default;
    }
    return is_string($v) ? $v : (string) $v;
}

function env_bool(string $key, bool $default = false): bool
{
    $v = env_string($key);
    if ($v === null) {
        return $default;
    }
    $normalized = strtolower(trim($v));
    return in_array($normalized, ['1', 'true', 'yes', 'on'], true);
}
