<?php

$ip = $_SERVER['REMOTE_ADDR'];
$file = sys_get_temp_dir() . "/limit_" . md5($ip);
$limit = 30; // número de peticiones permitidas
$interval = 60; // en segundos

$data = [];

if (file_exists($file)) {
    $data = json_decode(file_get_contents($file), true);
    $data = array_filter($data, fn($t) => time() - $t < $interval);
}

if (count($data) >= $limit) {
    http_response_code(429);
    echo json_encode([
        "status" => 429,
        "message" => "🚫 Demasiadas peticiones. Intenta más tarde."
    ]);
    exit;
}

$data[] = time();
file_put_contents($file, json_encode($data));
