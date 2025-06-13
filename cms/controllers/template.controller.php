<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once "extensions/vendor/phpmailer/phpmailer/src/Exception.php";
require_once "extensions/vendor/phpmailer/phpmailer/src/PHPMailer.php";
require_once "extensions/vendor/phpmailer/phpmailer/src/SMTP.php";

class TemplateController
{

	/*=============================================
	Traemos la vista principal de la plantilla
	=============================================*/

	public function index()
	{

		include "views/template.php";

	}

	/*=============================================
	Identificar el tipo de columna
	=============================================*/

	static public function typeColumn($value)
	{

		if (
			$value == "text" ||
			$value == "textarea" ||
			$value == "image" ||
			$value == "video" ||
			$value == "file" ||
			$value == "link" ||
			$value == "select" ||
			$value == "array" ||
			$value == "color" ||
			$value == "password" ||
			$value == "email"
		) {

			$type = "TEXT NULL DEFAULT NULL";
		}

		if ($value == "object") {

			$type = "TEXT NULL DEFAULT '{}'";
		}

		if ($value == "json") {

			$type = "TEXT NULL DEFAULT '[]'";

		}

		if ($value == "int" || $value == "relations" || $value == "order") {

			$type = "INT NULL DEFAULT '0'";

		}

		if ($value == "boolean") {

			$type = "INT NULL DEFAULT '1'";

		}

		if ($value == "double" || $value == "money") {

			$type = "DOUBLE NULL DEFAULT '0'";

		}

		if ($value == "date") {

			$type = "DATE NULL DEFAULT NULL";

		}

		if ($value == "time") {

			$type = "TIME NULL DEFAULT NULL";

		}

		if ($value == "datetime") {

			$type = "DATETIME NULL DEFAULT NULL";

		}

		if ($value == "timestamp") {

			$type = "TIMESTAMP on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP";
		}

		if ($value == "code" || $value == "chatgpt") {

			$type = "LONGTEXT NULL DEFAULT NULL";

		}

		return $type;

	}

	/*=============================================
	Función Reducir texto
	=============================================*/

	static public function reduceText($value, $limit)
	{

		if (strlen($value) > $limit) {

			$value = substr($value, 0, $limit) . "...";
		}

		return $value;
	}

	/*=============================================
	Devuelva la miniatura de la lista
	=============================================*/

	static public function returnThumbnailList($value)
	{

		/*=============================================
		Capturar miniatura imagen
		=============================================*/

		if (explode("/", $value->type_file)[0] == "image") {

			$path = '<img src="' . $value->link_file . '" class="rounded" style="width:100px; height:100px; object-fit: cover; object-position: center;">';

		}

		/*=============================================
		Capturar miniatura video
		=============================================*/

		if (explode("/", $value->type_file)[0] == "video" && $value->id_folder_file != 4) {

			if (explode("/", $value->type_file)[1] == "mp4") {

				$path = '<video class="rounded" style="width:100px; height:100px; object-fit: cover; object-position: center;">
				<source src="' . $value->link_file . '" type="' . $value->type_file . '">
				</video>';

			} else {

				$path = '<img src="/views/assets/img/multimedia.png" class="rounded" style="width:100px; height:100px; object-fit: cover; object-position: center;">';
			}

		}

		if (explode("/", $value->type_file)[0] == "video" && $value->id_folder_file == 4) {

			$path = '<img src="' . $value->thumbnail_vimeo_file . '" class="rounded" style="width:100px; height:100px; object-fit: cover; object-position: center;">';

		}

		/*=============================================
		Capturar miniatura audio
		=============================================*/

		if (explode("/", $value->type_file)[0] == "audio") {

			$path = '<img src="/views/assets/img/multimedia.png" class="rounded" style="width:100px; height:100px; object-fit: cover; object-position: center;">';

		}

		/*=============================================
		Capturar miniatura pdf
		=============================================*/

		if (explode("/", $value->type_file)[1] == "pdf") {

			$path = '<img src="/views/assets/img/pdf.jpeg" class="rounded" style="width:100px; height:100px; object-fit: cover; object-position: center;">';
		}

		/*=============================================
		Capturar miniatura zip
		=============================================*/

		if (explode("/", $value->type_file)[1] == "zip") {

			$path = '<img src="/views/assets/img/zip.jpg" class="rounded" style="width:100px; height:100px; object-fit: cover; object-position: center;">';
		}

		return $path;
	}

	/*=============================================
	Devuelva la miniatura de la cuadrícula
	=============================================*/

	static public function returnThumbnailGrid($value)
	{

		/*=============================================
		Capturar miniatura imagen
		=============================================*/

		if (explode("/", $value->type_file)[0] == "image") {

			$path = '<img src="' . $value->link_file . '" class="rounded card-img-top w-100">';

		}

		/*=============================================
		Capturar miniatura video
		=============================================*/

		if (explode("/", $value->type_file)[0] == "video" && $value->id_folder_file != 4) {

			if (explode("/", $value->type_file)[1] == "mp4") {

				$path = '<video class="rounded card-img-top w-100">
					<source src="' . $value->link_file . '" type="' . $value->type_file . '">
				</video>';

			} else {

				$path = '<img src="/views/assets/img/multimedia.png" class="rounded card-img-top w-100">';
			}

		}

		if (explode("/", $value->type_file)[0] == "video" && $value->id_folder_file == 4) {

			$path = '<img src="' . $value->thumbnail_vimeo_file . '" class="rounded card-img-top w-100">';

		}

		/*=============================================
		Capturar miniatura audio
		=============================================*/

		if (explode("/", $value->type_file)[0] == "audio") {

			$path = '<img src="/views/assets/img/multimedia.png" class="rounded card-img-top w-100">';

		}

		/*=============================================
		Capturar miniatura pdf
		=============================================*/

		if (explode("/", $value->type_file)[1] == "pdf") {

			$path = '<img src="/views/assets/img/pdf.jpeg" class="rounded card-img-top w-100">';
		}

		/*=============================================
	   Capturar miniatura zip
	   =============================================*/

		if (explode("/", $value->type_file)[1] == "zip") {

			$path = '<img src="/views/assets/img/zip.jpg" class="rounded card-img-top w-100">';
		}

		return $path;
	}

	/*=============================================
	Función para generar códigos alfanuméricos aleatorios
	=============================================*/

	static public function genPassword($length)
	{

		$password = "";
		$chain = "0123456789abcdefghijklmnopqrstuvwxyz";

		$password = substr(str_shuffle($chain), 0, $length);

		return $password;
	}

	/*=============================================
	Función para enviar correos electrónicos
	=============================================*/

	static public function sendEmail($subject, $email, $title, $message, $link, $extraLink = null)
	{
		date_default_timezone_set("America/Guayaquil");
		$mail = new PHPMailer(true);

		try {
			$mail->CharSet = 'UTF-8';
			$mail->isSMTP();
			$mail->Host = 'smtp.hostinger.com';
			$mail->SMTPAuth = true;
			$mail->Username = 'pruebas@proyectoecuador.com';
			$mail->Password = 'CodeC1ma.'; // ✅ reemplaza si cambias credenciales
			$mail->SMTPSecure = 'tls';
			$mail->Port = 587;

			$mail->setFrom('pruebas@proyectoecuador.com', 'Proyecto Ecuador');
			$mail->addAddress($email);
			$mail->Subject = $subject;

			// Botones según cantidad de enlaces
			if (!$extraLink) {
				$botones = '
			<a href="' . $link . '" target="_blank" style="text-decoration:none;">
				<div style="background:#000; color:white; padding:10px 20px; border-radius:5px; display:inline-block; margin-bottom:10px;">
					Realizar seguimiento
				</div>
			</a>';
			} else {
				$botones = '
			<table align="center" cellspacing="0" cellpadding="0" style="margin: 20px auto;">
			<tr>
				<td align="center" style="padding: 0 8px;">
				<a href="' . $extraLink . '" target="_blank" style="text-decoration:none;">
					<div style="background:#25D366; color:white; padding:12px 24px; border-radius:6px; font-weight:bold; display:inline-block;">
					Unirme al grupo de WhatsApp
					</div>
				</a>
				</td>
				<td align="center" style="padding: 0 8px;">
				<a href="' . $link . '" target="_blank" style="text-decoration:none;">
					<div style="background:#000; color:white; padding:12px 24px; border-radius:6px; font-weight:bold; display:inline-block;">
					Ver mi pedido
					</div>
				</a>
				</td>
			</tr>
			</table>';
			}

			$mail->msgHTML('
			<div style="width:100%; background:#eee; font-family:sans-serif; padding:40px 0;">
				<div style="margin:auto; width:600px; background:white; padding:20px; text-align:center;">
					<h3 style="color:#999;">' . $title . '</h3>
					<hr style="border:1px solid #ccc; width:80%">
					<div style="font-size:15px; color:#333;">' . $message . '</div>
					<div style="margin-top: 20px;">' . $botones . '</div>
					<hr style="border:1px solid #ccc; width:80%">
					<h5 style="color:#999; font-weight:100;">Si no solicitaste este correo, puedes ignorarlo.</h5>
				</div>
			</div>');
			$mail->send();
			return "ok";

		} catch (Exception $e) {
			error_log("⚠️ Error al enviar correo: " . $mail->ErrorInfo);
			return "Mailer Error: " . $mail->ErrorInfo;
		}
	}


}

?>