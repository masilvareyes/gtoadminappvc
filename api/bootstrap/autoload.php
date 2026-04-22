<?php

declare(strict_types=1);

/**
 * Autoload PSR-4 mínimo cuando no existe vendor/autoload.php de Composer.
 * Namespace App\Core\* → directorio app/core/* (minúsculas, alineado a docs).
 */
spl_autoload_register(static function (string $class): void {
    $prefix = 'App\\';
    if (!str_starts_with($class, $prefix)) {
        return;
    }
    $relative = substr($class, strlen($prefix));
    $parts = explode('\\', $relative);
    if (($parts[0] ?? '') === 'Core') {
        $parts[0] = 'core';
    }
    $relativePath = implode(DIRECTORY_SEPARATOR, $parts);
    $base = dirname(__DIR__) . DIRECTORY_SEPARATOR;
    $file = $base . $relativePath . '.php';
    if (is_file($file)) {
        require $file;
    }
});
