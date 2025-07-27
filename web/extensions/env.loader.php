<?php

use Dotenv\Dotenv;

// Carga el autoload de Composer
require_once __DIR__ . '/vendor/autoload.php';

// Carga el archivo .env desde la raíz del proyecto
$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();
