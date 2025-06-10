<?php

class OrdersController
{
  public function orderCreate()
  {
    if (isset($_POST["name"])) {

      echo '<div class="col-12 mx-1 mb-3 text-center alert alert-warning"><div class="spinner-border spinner-border-sm"></div> Procesando su pedido, será redirigido a nuestra pasarela de pagos...</div>';

      // Traemos el sorteo
      $url = "raffles?linkTo=id_raffle,status_raffle&equalTo=" . $_POST["raffle"] . ",1&select=id_raffle,price_raffle,group_ws_raffle,email_raffle,type_number_raffle";
      $method = "GET";
      $fields = array();
      $raffle = CurlController::request($url, $method, $fields);


      if ($raffle->status == 200) {
        $raffle = $raffle->results[0];
      } else {
        echo '<div class="col-12 mx-1 mb-3 text-center alert alert-danger">ERROR: El Sorteo no se encuentra disponible, comunicarse con Soporte</div>';
        return;
      }

      // Capturar el precio y total
      $isDynamic = is_numeric($_POST["numbers"]);
      $numbers = explode(",", $_POST["numbers"]);
      $total = ($isDynamic ? intval($_POST["numbers"]) : count($numbers)) * $raffle->price_raffle;

      if (!$isDynamic) {
        foreach ($numbers as $value) {
          $url = "sales?linkTo=number_sale,id_raffle_sale&equalTo=" . $value . "," . $_POST["raffle"];
          $getNumber = CurlController::request($url, "GET", []);

          if ($getNumber->status == 200 && count($getNumber->results) > 0) {
            $estado = strtoupper($getNumber->results[0]->status_sale ?? '');

            // ❌ Si ya fue pagado, rechazar
            if ($estado === 'PAID') {
              echo '<div class="col-12 mx-1 mb-3 text-center alert alert-danger">ERROR: "El número ' . $value . ' ya está pagado por otra persona, elige otro número"</div>';
              return;
            }

          }
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

      if ($createOrder->status != 200) {
        echo '<div class="col-12 mx-1 mb-3 text-center alert alert-danger">ERROR: No se pudo crear la orden</div>';
        return;
      }


      if ($raffle->type_number_raffle === "estatico") {
        $ventasFallidas = [];
        $errores = [];

        foreach ($numbers as $number) {

          // Validar si el número ya fue pagado (estado PAID)
          $url = "sales?linkTo=number_sale,id_raffle_sale&equalTo=" . $number . "," . $raffle->id_raffle;
          $verifica = CurlController::request($url, "GET", []);

          if ($verifica->status == 200 && count($verifica->results) > 0) {
            $estado = strtoupper($verifica->results[0]->status_sale ?? '');

            // Si ya fue pagado, bloquear
            if ($estado === 'PAID') {
              $errores[] = "N° $number → ya fue comprado por otra persona";
              continue;
            }

            // Si ya existe en PENDING, dejarlo pasar (es válido)
          }

          // Crear la venta
          $fields = [
            "id_raffle_sale" => $raffle->id_raffle,
            "id_client_sale" => $clientId,
            "id_order_sale" => $createOrder->results->lastId,
            "number_sale" => $number,
            "status_sale" => "PENDING",
            "date_created_sale" => date("Y-m-d")
          ];

          $venta = CurlController::request("sales?token=no&except=id_sale", "POST", $fields);

          if (!$venta || !isset($venta->status) || $venta->status != 200) {
            $errores[] = "N° $number → " . ($venta->results ?? 'error desconocido');
          }
        }

        if (count($errores) > 0) {
          echo '<div class="alert alert-danger">❌ Error al crear ventas para los siguientes números:<br>' . implode("<br>", $errores) . '</div>';
          return;
        }
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