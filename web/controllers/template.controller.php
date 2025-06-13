<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require_once "extensions/vendor/phpmailer/phpmailer/src/Exception.php";
require_once "extensions/vendor/phpmailer/phpmailer/src/PHPMailer.php";
require_once "extensions/vendor/phpmailer/phpmailer/src/SMTP.php";

class TemplateController
{

	/*=============================================
						 Traemos la Vista Principal de la plantilla
						 =============================================*/

	public function index()
	{

		include "views/template.php";

	}

	/*=============================================
						 Función para dar formato a las fechas
						 =============================================*/

	static public function formatDate($type, $value)
	{

		date_default_timezone_set("America/Guayaquil");
		setlocale(LC_TIME, 'es.UTF-8', 'esp'); //Para traer dias y meses en español

		if ($type == 1) {

			return strftime("%d de %B, %Y", strtotime($value));
		}

		if ($type == 2) {

			return strftime("%b %Y", strtotime($value));

		}

		if ($type == 3) {

			return strftime("%d - %m - %Y", strtotime($value));

		}

		if ($type == 4) {

			if (strftime("%H", strtotime($value)) < 13) {

				$abr = "AM";

			} else {

				$abr = "PM";
			}

			return strftime("%A %d de %B %Y a las %I " . $abr, strtotime($value));

		}

		if ($type == 5) {

			return strftime("%D", strtotime($value));

		}

	}

	/*=============================================
						 Función para mayúscula inicial
						 =============================================*/

	static public function capitalize($value)
	{

		$value = mb_convert_case($value, MB_CASE_TITLE, "UTF-8");
		return $value;

	}

	/*=============================================
						 Función para generar códigos numéricos aleatorios
						 =============================================*/

	static public function genCodec($length)
	{

		$codec = rand(1 * $length, (10 * $length) - 1) . Time();

		return $codec;
	}

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
			$mail->Password = 'CodeC1ma.';
			$mail->SMTPSecure = 'tls';
			$mail->Port = 587;

			$mail->setFrom('pruebas@proyectoecuador.com', 'Proyecto Ecuador');
			$mail->addAddress($email);
			$mail->Subject = $subject;

			// Si solo hay 1 link → botón con "Realizar seguimiento"
			if (!$extraLink) {
				$botones = '
				<a href="' . $link . '" target="_blank" style="text-decoration:none;">
					<div style="background:#000; color:white; padding:10px 20px; border-radius:5px; display:inline-block; margin-bottom:10px;">
						Realizar seguimiento
					</div>
				</a>';
			} else {
				// Si hay dos links → mostrar ambos botones en una misma fila
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
