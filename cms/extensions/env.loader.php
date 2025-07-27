<?php
use Dotenv\Dotenv;

require_once __DIR__ . '/vendor/autoload.php'; // <- Composer CARGADO AQUÍ

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();
