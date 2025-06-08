
<?php

class OrdersController
{
  public function orderCreate()
  {
    if (isset($_POST["name"])) {

      echo '<div class="col-12 mx-1 mb-3 text-center alert alert-warning"><div class="spinner-border spinner-border-sm"></div> Procesando su pedido, será redirigido a nuestra pasarela de pagos...</div>';

      // Traemos el sorteo
      $url = "raffles?linkTo=id_raffle,status_raffle&equalTo=" . $_POST["raffle"] . ",1&select=id_raffle,price_raffle,group_ws_raffle,email_raffle";
      $method = "GET";
      $fields = array();
      $raffle = CurlController::request($url, $method, $fields);

      echo "<pre>Respuesta del sorteo:
";
      print_r($raffle);
      echo "</pre>";

      if ($raffle->status == 200) {
        $raffle = $raffle->results[0];
      } else {
        echo '<div class="col-12 mx-1 mb-3 text-center alert alert-danger">ERROR: El Sorteo no se encuentra disponible, comunicarse con Soporte</div>';
        return;
      }

      // Capturar el precio y total
      $numbers = explode(",", $_POST["numbers"]);
      $total = count($numbers) * $raffle->price_raffle;

      // Validar que los números no estén vendidos
      foreach ($numbers as $value) {
        $url = "sales?linkTo=number_sale,id_raffle_sale&equalTo=" . $value . "," . $_POST["raffle"];
        $getNumber = CurlController::request($url, "GET", []);
        if ($getNumber->status == 200) {
          echo '<div class="col-12 mx-1 mb-3 text-center alert alert-danger">ERROR: "El número ' . $value . ' ya está adquirido por otra persona, elige otro número"</div>';
          return;
        }
      }

      // Verificar si cliente ya existe
      $url = "clients?linkTo=email_client&equalTo=" . trim($_POST["email"]);
      $getClient = CurlController::request($url, "GET", []);

      if ($getClient->status == 200 && count($getClient->results) > 0) {
        $clientId = $getClient->results[0]->id_client;
      } else {
        // Crear nuevo cliente
        $url = "clients?token=no&except=id_client";
        $fields = array(
          "name_client" => TemplateController::capitalize(trim($_POST["name"])),
          "surname_client" => TemplateController::capitalize(trim($_POST["surname"])),
          "phone_client" => trim($_POST["whatsapp"]),
          "email_client" => trim($_POST["email"]),
          "date_created_client" => date("Y-m-d")
        );
        $createClient = CurlController::request($url, "POST", $fields);

        echo "<pre>Cliente creado:
";
        print_r($createClient);
        echo "</pre>";

        if ($createClient->status != 200) {
          echo '<div class="col-12 mx-1 mb-3 text-center alert alert-danger">ERROR: No se pudo crear el cliente</div>';
          return;
        }

        $clientId = $createClient->results->lastId;
      }

      // Crear orden
      $ref = TemplateController::genCodec(11);
      $url = "orders?token=no&except=id_order";
      $fields = array(
        "ref_order" => $ref,
        "id_raffle_order" => $raffle->id_raffle,
        "id_client_order" => $clientId,
        "numbers_order" => $_POST["numbers"],
        "total_order" => $total,
        "method_order" => $_POST["optradio"],
        "status_order" => "PENDING",
        "date_created_order" => date("Y-m-d")
      );
      $createOrder = CurlController::request($url, "POST", $fields);

      echo "<pre>Orden creada:
";
      print_r($createOrder);
      echo "</pre>";

      if ($createOrder->status != 200) {
        echo '<div class="col-12 mx-1 mb-3 text-center alert alert-danger">ERROR: No se pudo crear la orden</div>';
        return;
      }

      // Redirigir a PayPal
      if ($_POST["optradio"] == "paypal") {
        $urlReturn = $_SERVER["REQUEST_SCHEME"] . "://" . $_SERVER["SERVER_NAME"];
        $fields = '{
          "intent": "CAPTURE",
          "purchase_units": [{
            "reference_id": "' . $ref . '",
            "amount": {
              "currency_code": "USD",
              "value": "' . number_format($total, 2, '.', '') . '"
            }
          }],
          "payment_source": {
            "paypal": {
              "experience_context": {
                "payment_method_preference": "IMMEDIATE_PAYMENT_REQUIRED",
                "user_action": "PAY_NOW",
                "return_url": "' . $urlReturn . '/thanks?ref=' . $ref . '",
                "cancel_url": "' . $urlReturn . '/checkout?numbers=' . $_POST["numbers"] . '"
              }
            }
          }
        }';

        $paypal = CurlController::paypal('v2/checkout/orders', 'POST', $fields);

        if (isset($paypal->status) && $paypal->status == "PAYER_ACTION_REQUIRED") {
          $url = "orders?id=" . $createOrder->results->lastId . "&nameId=id_order&token=no&except=id_pay_order";
          $fields = "id_pay_order=" . $paypal->id;
          $updateOrder = CurlController::request($url, "PUT", $fields);

          if ($updateOrder->status == 200) {
            // Enviar correo al admin
            $subject = "[ProyectoEcuador] Pedido recibido por $" . number_format($total, 2);
            $email = $raffle->email_raffle ?? 'admin@proyectoecuador.com';
            $title = "[ProyectoEcuador] Pedido # " . $ref;
            $message = "
              <p>De <strong>" . TemplateController::capitalize($_POST["name"]) . " " . TemplateController::capitalize($_POST["surname"]) . "</strong>,</p>
              <p>📞 Whatsapp: " . trim($_POST["whatsapp"]) . "<br>
              📧 Email: " . trim($_POST["email"]) . "</p>
              <p><strong>Número(s):</strong> <strong>" . $_POST["numbers"] . "</strong></p>";
            TemplateController::sendEmail($subject, $email, $title, $message, "https://proyectoecuador.com/ingresar");

            echo "<script>window.location = '" . $paypal->links[1]->href . "';</script>";
          }
        } else {
          echo '<div class="col-12 mx-1 mb-3 text-center alert alert-danger">ERROR: PayPal presenta errores, intenta con otro medio de pago</div>';
          return;
        }
      }
    }
  }
}
?>
