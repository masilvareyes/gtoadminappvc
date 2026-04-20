<?php

declare(strict_types=1);

$root = dirname(__DIR__);

if (is_file($root . '/vendor/autoload.php')) {
    require $root . '/vendor/autoload.php';
} else {
    require $root . '/app/bootstrap/autoload.php';
}

$app = require $root . '/app/bootstrap/app.php';
$app();
