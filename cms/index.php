<?php 
/*=============================================
Depurar Errores
=============================================*/

define('DIR',__DIR__);

ini_set("display_errors", 1);
ini_set("log_errors", 1);
ini_set("error_log", DIR."/php_error_log");

/*=============================================
Autoload Composer (¡primero!)
=============================================*/
require_once "extensions/vendor/autoload.php";

/*=============================================
Cargar Variables de Entorno
=============================================*/
require_once "extensions/env.loader.php";

/*=============================================
Requerimientos del sistema
=============================================*/
require_once "controllers/template.controller.php";
require_once "controllers/curl.controller.php";

/*=============================================
Plantilla
=============================================*/

$index = new TemplateController();
$index -> index();

?>