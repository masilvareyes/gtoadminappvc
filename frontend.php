<?php

declare(strict_types=1);

require_once __DIR__ . '/api/bootstrap/env.php';

/**
 * Cargar .env para poder usar APP_BASE_PATH también en frontend
 */
load_env(__DIR__ . DIRECTORY_SEPARATOR . '.env');

$basePath = env_string('APP_BASE_PATH', '');
$basePath = rtrim($basePath, '/');

/*
echo '<pre>';
echo '__DIR__: ' . __DIR__ . PHP_EOL;
echo 'env helper: ' . __DIR__ . '/api/bootstrap/env.php' . PHP_EOL;
echo '.env path: ' . __DIR__ . '/.env' . PHP_EOL;
echo 'env helper exists: ' . (is_file(__DIR__ . '/api/bootstrap/env.php') ? 'YES' : 'NO') . PHP_EOL;
echo '.env exists: ' . (is_file(__DIR__ . '/.env') ? 'YES' : 'NO') . PHP_EOL;
echo '</pre>';
*/

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

http_response_code(200);
header('Content-Type: text/html; charset=utf-8');
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gastos VC2</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= e($basePath) ?>/assets/css/styles/app.css">
</head>
<body>
<div class="container py-4">
    <header class="mb-3">
        <h1 class="h4 mb-1">Gastos VC2</h1>
        <p class="text-muted mb-0">Frontend Base (MVC + Router JS)</p>
    </header>

    <nav class="nav nav-pills mb-3">
        <a class="nav-link" href="#/">Inicio</a>
        <a class="nav-link" href="#/login">Login (dummy)</a>
    </nav>

    <section id="global-tech-error" class="alert alert-danger d-none mb-3" role="alert"></section>

    <main id="app-view" class="card">
        <div class="card-body">Cargando...</div>
    </main>
</div>

<script>
    window.APP_CONFIG = {
        BASE_PATH: "<?= e($basePath) ?>"
    };
</script>

<script src="<?= e($basePath) ?>/assets/vendors/jquery/jquery-3.7.1.min.js"></script>

<script src="<?= e($basePath) ?>/assets/js/config/app.config.js"></script>
<script src="<?= e($basePath) ?>/assets/js/config/api.config.js"></script>

<script src="<?= e($basePath) ?>/assets/js/helpers/error.helper.js"></script>

<script src="<?= e($basePath) ?>/assets/js/services/api-client.js"></script>
<script src="<?= e($basePath) ?>/assets/js/services/health.service.js"></script>
<script src="<?= e($basePath) ?>/assets/js/services/db-health.service.js"></script>

<script src="<?= e($basePath) ?>/pages/home.page.js"></script>
<script src="<?= e($basePath) ?>/pages/login.page.js"></script>

<script src="<?= e($basePath) ?>/assets/js/router/routes.js"></script>
<script src="<?= e($basePath) ?>/assets/js/router/router.js"></script>

<script src="<?= e($basePath) ?>/assets/js/app/init.js"></script>
<script src="<?= e($basePath) ?>/assets/js/app/app.js"></script>
</body>
</html>