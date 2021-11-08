<?php

use Dotenv\Dotenv;

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__ . '/..', '.env');
$dotenv->load();
$dotenv->required(['DB_DSN', 'DB_USER'])->notEmpty();
$dotenv->required(['APP_DEBUG', 'APP_DISPLAY_ERROR_DETAILS'])->notEmpty()->isBoolean();
$dotenv->required(['DB_PASS']);
