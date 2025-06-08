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

				$numbers = explode(",", $order->numbers_order);
				$totalSales = 0;

				// Verificar disponibilidad de cada número
				foreach ($numbers as $number) {
					$checkUrl = "sales?linkTo=number_sale,id_raffle_sale&equalTo={$number},{$order->id_raffle}";
					$check = CurlController::request($checkUrl, "GET", []);
					if ($check->status == 200) {
						echo "<div class='alert alert-danger text-center'>❌ El número <strong>{$number}</strong> ya fue tomado por otro usuario. No se puede completar la orden. Contacta con soporte.</div>";
						return;
					}
				}

				// Crear las ventas
				foreach ($numbers as $number) {
					$url = "sales?token=no&except=id_sale";
					$method = "POST";
					$fields = array(
						"id_raffle_sale" => $order->id_raffle,
						"id_client_sale" => $order->id_client,
						"id_order_sale" => $order->id_order,
						"number_sale" => $number,
						"status_sale" => "PAID",
						"date_created_sale" => date("Y-m-d")
					);

					$createSale = CurlController::request($url, $method, $fields);

					if ($createSale->status == 200) {
						$totalSales++;
					} else {
						echo '<div class="alert alert-danger text-center">❌ Error al registrar el número ' . $number . '. Contacta con soporte.</div>';
						return;
					}
				}

				// Actualizar estado de la orden
				$url = "orders?id=" . $order->id_order . "&nameId=id_order&token=no&except=id_order";
				$method = "PUT";
				$fields = http_build_query(["status_order" => $status]);
				CurlController::request($url, $method, $fields);

				// Enviar correo al cliente
				$subjectClient = "🎉 ¡Gracias por tu compra " . TemplateController::capitalize($order->name_client) . "!";
				$emailClient = trim($order->email_client);
				$titleClient = "[ProyectoEcuador] Pedido # " . $order->ref_order;

				$messageClient = "
				<p>Hola <strong>" . TemplateController::capitalize($order->name_client) . " " . TemplateController::capitalize($order->surname_client) . "</strong>,</p>
				<p>Gracias por participar en nuestro sorteo 🎊</p>
				<p><strong>Estos son tus números:</strong></p>
				<h1 style='margin: 10px 0;'>" . $order->numbers_order . "</h1>
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
