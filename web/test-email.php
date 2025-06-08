<?php

require_once "controllers/template.controller.php";

// ⚠️ Reemplaza por tu correo real para hacer la prueba
$to = "ed.gualle@gmail.com";

$response = TemplateController::sendEmail(
    "🧾 Prueba de envío de correo",
    $to,
    "Este es el título del correo",
    "Hola Edison, esta es una prueba de tu sistema con PHPMailer desde Hostinger.",
    "https://proyectoecuador.com"
);

echo "<pre>";
echo "Resultado del envío: $response";
echo "</pre>";
