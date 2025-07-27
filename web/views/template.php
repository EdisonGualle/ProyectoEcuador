<?php

/*==================================
Capturar las rutas de la URL
==================================*/

$routesArray = explode("/", $_SERVER["REQUEST_URI"]);
array_shift($routesArray);

foreach ($routesArray as $key => $value) {
	$routesArray[$key] = explode("?", $value)[0];
}

/*==================================
EXCEPCIÓN: Si se accede a /payphone/confirmacion, cargar archivo directamente
==================================*/

if (!empty($routesArray[0]) && $routesArray[0] === "payphone" && $routesArray[1] === "confirmacion") {
	include "views/pages/payphone/confirmacion.php";
	return;
}

/*=============================================
Validar existencia de sorteo
=============================================*/

$url = "relations?rel=raffles,products&type=raffle,product&linkTo=status_raffle&equalTo=1&orderBy=id_raffle&orderMode=ASC";
$method = "GET";
$fields = array();

$raffle = CurlController::request($url, $method, $fields);

if ($raffle->status == 200) {
	$raffle = $raffle->results[0];
} else {
	$raffle = null;
}

/*==================================
Traer info de la plantilla
==================================*/

$url = "templates?linkTo=status_template&equalTo=1&orderBy=id_template&orderMode=ASC";

$method = "GET";
$fields = array();

$template = CurlController::request($url, $method, $fields);

if ($template->status == 200) {
	$template = $template->results[0];
} else {
	$template = null;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- SEO básico -->
	<title>Proyecto Ecuador - Sorteo de una Casa en Quito</title>
	<meta name="description" content="Participa en el sorteo de una casa de 538 m² en Quito. Sorteo transparente, legal y con documentación en regla. ¡Compra tu número ahora en Proyecto Ecuador!">
	<meta name="keywords" content="sorteo, casa, Quito, Ecuador, Proyecto Ecuador, lotería, ganar casa, sorteo legal, Rumiñahui, rifas">
	<link rel="canonical" href="https://<?=$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']?>">

	<!-- Favicon -->
	<link rel="icon" type="image/png" href="/views/assets/img/ProyectoEcuadorLogo.png">

	<!-- Open Graph / Facebook -->
	<meta property="og:title" content="Sorteo de una Casa en Quito - Proyecto Ecuador">
	<meta property="og:description" content="Una casa con terreno de 538m² y 122m² de construcción será sorteada legalmente en Quito. Participa comprando tu número y gana.">
	<meta property="og:image" content="https://<?=$_SERVER['HTTP_HOST']?>/views/assets/img/ProyectoEcuadorLogo.png">
	<meta property="og:url" content="https://<?=$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']?>">
	<meta property="og:type" content="website">
	<meta property="og:locale" content="es_EC">

	<!-- Twitter Card -->
	<meta name="twitter:card" content="summary_large_image">
	<meta name="twitter:title" content="Sorteo de una Casa en Quito - Proyecto Ecuador">
	<meta name="twitter:description" content="Casa de 538m² de terreno en Rumiñahui, Quito. Sorteo válido y legal, participa con tu número.">
	<meta name="twitter:image" content="https://<?=$_SERVER['HTTP_HOST']?>/views/assets/img/ProyectoEcuadorLogo.png">

	<!-- Fonts y estilos -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,100..700;1,100..700&display=swap" rel="stylesheet">

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
	<link rel="stylesheet" href="/views/assets/css/coming_soon.css">
	<link rel="stylesheet" href="/views/assets/css/style.css">

	<?php if ($template == null): ?>
		<link rel="stylesheet" href="/views/assets/css/style.css">
	<?php else: ?>
		<?php include "views/assets/css/style.css.php" ?>
	<?php endif ?>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script src="https://unpkg.com/imask@7.6.1/dist/imask.js"></script>
</head>


<body <?php if ($raffle == null): ?> class="coming-soon" <?php endif ?>>
	<?php
	if (!empty($raffle)) {

		include "views/modules/top/top.php";

		if (!empty($routesArray[0])) {
			$page = $routesArray[0];
			$subpage = $routesArray[1] ?? null;

			if ($subpage) {
				$path = "views/pages/$page/$subpage.php";
			} else {
				$path = "views/pages/$page/$page.php";
			}

			if (file_exists($path)) {
				include $path;
			} else {
				include "views/pages/home/home.php";
			}
		} else {
			include "views/pages/home/home.php";
		}

		include "views/modules/footer/footer.php";

	} else {
		include "views/pages/coming-soon/coming-soon.php";
	}
	?>

	<script src="/views/assets/js/countdown/countdown.js"></script>
	<script src="/views/assets/js/main/main.js"></script>
	<script src="/views/assets/js/video/video.js"></script>
</body>
</html>
