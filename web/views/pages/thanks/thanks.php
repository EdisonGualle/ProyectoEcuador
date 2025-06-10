<?php

$status = "";

if (isset($_GET["ref"])) {

	$url = "relations?rel=orders,clients,raffles&type=order,client,raffle&linkTo=ref_order&equalTo=" . $_GET["ref"];
	$method = "GET";
	$fields = array();

	$order = CurlController::request($url, $method, $fields);

	if ($order->status == 200) {

		$order = $order->results[0];

		if ($order->status_order == "PENDING") {

			if ($order->method_order == "paypal") {

				$url = "v2/checkout/orders/" . $order->id_pay_order;
				$paypal = CurlController::paypal($url, "GET", []);

				if (!isset($paypal->status)) {
					echo "<div class='alert alert-danger text-center'>❌ No se pudo verificar el estado del pago. Intenta nuevamente.</div>";
					return;
				}

				if ($paypal->status !== "APPROVED") {
					echo "<div class='alert alert-warning text-center'>⚠️ El pago fue cancelado o no se completó. Tu pedido sigue pendiente.</div>";
					return;
				}

				$status = "PAID";

			}

			if (isset($status) && $status === "PAID") {

				if ($order->type_number_raffle === "dinamico") {

					$payload = [
						"id_raffle" => $order->id_raffle,
						"id_client" => $order->id_client,
						"id_order" => $order->id_order,
						"status_sale" => "PAID"
					];

					$generate = CurlController::request("sales?random=sales", "POST", $payload);

					if ($generate->status != 200 || empty($generate->numbers)) {
						echo '<div class="alert alert-danger text-center">❌ Error al generar los números aleatorios. Contacta con soporte.</div>';
						return;
					}

					$numbers = $generate->numbers;

				} else {
					// Obtener números del pedido
					$numbers = explode(",", $order->numbers_order);

					// Actualizar ventas de estático a PAID
					foreach ($numbers as $number) {
						$urlSale = "sales?linkTo=number_sale,id_raffle_sale&equalTo=" . $number . "," . $order->id_raffle;
						$res = CurlController::request($urlSale, "GET", []);

						if ($res->status == 200 && count($res->results) > 0) {
							$saleId = $res->results[0]->id_sale;

							$updateFields = http_build_query([
								"status_sale" => "PAID",
								"date_updated_sale" => date("Y-m-d H:i:s")
							]);

							$urlUpdate = "sales?id=$saleId&nameId=id_sale&token=no&except=id_sale";
							$updateRes = CurlController::request($urlUpdate, "PUT", $updateFields);
						}
					}

				}


				// Cambiar estado de orden a PAID
				$url = "orders?id=" . $order->id_order . "&nameId=id_order&token=no&except=id_order";
				$fields = http_build_query(["status_order" => $status]);
				CurlController::request($url, "PUT", $fields);

				// Enviar correo
				$subjectClient = "🎉 ¡Gracias por tu compra " . TemplateController::capitalize($order->name_client) . "!";
				$emailClient = trim($order->email_client);
				$titleClient = "[ProyectoEcuador] Pedido # " . $order->ref_order;

				$messageClient = "
        <p>Hola <strong>" . TemplateController::capitalize($order->name_client) . " " . TemplateController::capitalize($order->surname_client) . "</strong>,</p>
        <p>Gracias por participar en nuestro sorteo 🎊</p>
        <p><strong>Estos son tus números:</strong></p>
        <h1 style='margin: 10px 0;'>" . implode(",", $numbers) . "</h1>
        <p>📢 <strong>¡ATENCIÓN!</strong></p>
        <p>Únete al grupo de WhatsApp para recibir actualizaciones y noticias del sorteo</p>
        <p style='margin-top: 20px;'>🍀 ¡Te deseamos mucha suerte!</p>";

				$urlReturn = $_SERVER["REQUEST_SCHEME"] . "://" . $_SERVER["SERVER_NAME"];
				$linkPedido = $urlReturn . "/thanks?ref=" . $order->ref_order;
				$linkWhatsapp = $order->group_ws_raffle ?? 'https://proyectoecuador.com/ingresar';

				$sendEmail = TemplateController::sendEmail($subjectClient, $emailClient, $titleClient, $messageClient, $linkPedido, $linkWhatsapp);

				if ($sendEmail == "ok") {
					echo '<div class="col-12 mx-1 mb-3 text-center alert alert-success">✅ Su pago ha sido acreditado. Revisa tu correo electrónico 📩</div>';
				} else {
					echo '<div class="col-12 mx-1 mb-3 text-center alert alert-warning">⚠️ Su pago fue exitoso, pero hubo un problema al enviar el correo</div>';
				}
			}


		} else {
			$status = "PAID";
			$numbers = explode(",", $order->numbers_order);
		}

		include "modules/hero/hero.php";
		include "modules/main/main.php";

	} else {
		echo "<script>window.location = '/';</script>";
	}
} else {
	echo "<script>window.location = '/';</script>";
}
?>