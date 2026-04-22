<?php

declare(strict_types=1);

$root = __DIR__;

if (is_file($root . '/vendor/autoload.php')) {
    require $root . '/vendor/autoload.php';
} else {
    require $root . '/api/bootstrap/autoload.php';
}

$app = require $root . '/api/bootstrap/app.php';
$app();
