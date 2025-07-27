<?php

class DynamicController
{

	/*=============================================
	Gestión de datos dinámicos
	=============================================*/

	public function manage()
	{

		if (isset($_POST["module"])) {

			echo '<script>

				fncMatPreloader("on");
			    fncSweetAlert("loading", "Procesando...", "");

			</script>';

			$module = json_decode($_POST["module"]);

			/*=============================================
			Editar datos
			=============================================*/

			if (isset($_POST["idItem"])) {

				/*=============================================
				Actualizar datos
				=============================================*/

				$url = $module->title_module . "?id=" . base64_decode($_POST["idItem"]) . "&nameId=id_" . $module->suffix_module . "&token=" . $_SESSION["admin"]->token_admin . "&table=admins&suffix=admin";
				$method = "PUT";
				$fields = "";
				$count = 0;

				foreach ($module->columns as $key => $value) {

					if ($value->type_column == "password" && !empty($_POST[$value->title_column])) {

						$fields .= $value->title_column . "=" . crypt(trim($_POST[$value->title_column]), '$2a$07$azybxcags23425sdg23sdfhsd$') . "&";
					} else if ($value->type_column == "email") {

						$fields .= $value->title_column . "=" . trim($_POST[$value->title_column]) . "&";
					} else {

						// $fields.= $value->title_column."=".urlencode(trim($_POST[$value->title_column]))."&";
						$fields .= $value->title_column . "=" . (isset($_POST[$value->title_column]) ? urlencode(trim($_POST[$value->title_column])) : "") . "&";
					}

					$count++;

					if ($count == count($module->columns)) {

						$fields = substr($fields, 0, -1);

						$update = CurlController::request($url, $method, $fields);

						if ($update->status == 200) {



							if ($module->title_module === "orders" && isset($_POST["status_order"]) && $_POST["status_order"] === "PAID") {

								$id_order = base64_decode($_POST["idItem"]);

								$order = CurlController::request("orders?linkTo=id_order&equalTo=$id_order", "GET", []);
								if ($order->status == 200) {
									$order = $order->results[0];
									$id_client = $order->id_client_order;
									$id_raffle = $order->id_raffle_order;

									// ✅ Obtener datos del cliente
									$client = CurlController::request("clients?linkTo=id_client&equalTo=$id_client", "GET", []);
									if ($client->status == 200) {
										$client = $client->results[0];
										$email = trim($client->email_client ?? '');
										$nombre = ucwords(strtolower($client->name_client ?? ''));
										$apellido = ucwords(strtolower($client->surname_client ?? ''));
									} else {
										$email = '';
										$nombre = '';
										$apellido = '';
									}

									$raffle = CurlController::request("raffles?linkTo=id_raffle&equalTo=$id_raffle", "GET", []);
									if ($raffle->status == 200) {
										$raffle = $raffle->results[0];

										if ($raffle->type_number_raffle === "dinamico") {
											$payload = [
												"id_raffle" => $id_raffle,
												"id_client" => $id_client,
												"id_order" => $id_order,
												"status_sale" => "PAID"
											];
											CurlController::request("sales?random=sales", "POST", $payload);
										} else {
											$numbers = explode(",", $order->numbers_order);
											foreach ($numbers as $number) {
												$res = CurlController::request("sales?linkTo=number_sale,id_raffle_sale&equalTo=$number,$id_raffle", "GET", []);
												if ($res->status == 200 && count($res->results) > 0) {
													$saleId = $res->results[0]->id_sale;
													$fields = http_build_query([
														"status_sale" => "PAID",
														"date_updated_sale" => date("Y-m-d H:i:s")
													]);
													CurlController::request("sales?id=$saleId&nameId=id_sale&token=no&except=id_sale", "PUT", $fields);
												}
											}
										}

										// Obtener números generados
										$response = CurlController::request("sales?linkTo=id_order_sale&equalTo=$id_order&select=number_sale", "GET", []);
										$numbers = ($response->status == 200) ? array_map(fn($n) => $n->number_sale, $response->results) : [];

										// Construir correo
										$subject = "🎉 ¡Gracias por tu compra $nombre!";
										$title = "[ProyectoEcuador] Pedido # " . $order->ref_order;

										$message = "
										<p>Hola <strong>$nombre $apellido</strong>,</p>
										<p>Gracias por participar en nuestro sorteo 🎊</p>
										<p><strong>Estos son tus números:</strong></p>
										<h1 style='margin: 10px 0;'>" . implode(",", $numbers) . "</h1>
										<p>📢 <strong>¡ATENCIÓN!</strong></p>
										<p>Únete al grupo de WhatsApp para recibir actualizaciones y noticias del sorteo</p>
										<p style='margin-top: 20px;'>🍀 ¡Te deseamos mucha suerte!</p>";

										// Enlaces
										$urlReturn = $_SERVER["REQUEST_SCHEME"] . "://" . $_SERVER["SERVER_NAME"];
										$linkPedido = $urlReturn . "/thanks?ref=" . $order->ref_order;
										$linkWhatsapp = $raffle->group_ws_raffle ?? 'https://proyectoecuador.com/ingresar';

										// Enviar correo con ambos botones
										if ($order->email_sent_order === 'Enviado') {
											echo '<div class="alert alert-warning text-center">⚠️ Este pedido ya fue notificado por correo.</div>';
										} else {
											$sendEmail = TemplateController::sendEmail($subject, $email, $title, $message, $linkPedido, $linkWhatsapp);

											if ($sendEmail === 'ok') {
												CurlController::request("orders?id=$id_order&nameId=id_order&token=no&except=id_order", "PUT", http_build_query([
													"email_sent_order" => "Enviado"
												]));
											}
										}

									}
								}
							}


							echo '

								<script>

									fncMatPreloader("off");
									fncFormatInputs();
								    fncSweetAlert("success","El registro ha sido actualizado con éxito", setTimeout(()=>window.location="/' . $module->url_page . '",1000));
									

								</script>

							';
						}
					}
				}
			} else {

				/*=============================================
				Crear datos
				=============================================*/

				$url = $module->title_module . "?token=" . $_SESSION["admin"]->token_admin . "&table=admins&suffix=admin";
				$method = "POST";
				$fields = array();
				$count = 0;

				foreach ($module->columns as $key => $value) {

					if ($value->type_column == "password") {

						$fields[$value->title_column] = crypt(trim($_POST[$value->title_column]), '$2a$07$azybxcags23425sdg23sdfhsd$');
					} else if ($value->type_column == "email") {

						$fields[$value->title_column] = trim($_POST[$value->title_column]);
					} else {

						// $fields[$value->title_column] = urlencode(trim($_POST[$value->title_column]));
						$fields[$value->title_column] = isset($_POST[$value->title_column]) ? urlencode(trim($_POST[$value->title_column])) : "";
					}

					$count++;

					if ($count == count($module->columns)) {

						$fields["date_created_" . $module->suffix_module] = date("Y-m-d");

						$save = CurlController::request($url, $method, $fields);

						if ($save->status == 200) {

							echo '

								<script>

									fncMatPreloader("off");
									fncFormatInputs();
								    fncSweetAlert("success","El registro ha sido guardado con éxito", setTimeout(()=>window.location="/' . $module->url_page . '",1000));
									

								</script>

							';
						}
					}
				}
			}
		}
	}
}
