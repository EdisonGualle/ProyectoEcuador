<?php

class OrdersController
{
  public function orderCreate()
  {
    if (isset($_POST["name"])) {

      echo '<div class="col-12 mx-1 mb-3 text-center alert alert-warning"><div class="spinner-border spinner-border-sm"></div> Procesando su pedido, será redirigido a nuestra pasarela de pagos...</div>';

      // Traemos el sorteo
      $url = "raffles?linkTo=id_raffle,status_raffle&equalTo=" . $_POST["raffle"] . ",1&select=id_raffle,price_raffle,group_ws_raffle,email_raffle,type_number_raffle,phone_raffle";
      $method = "GET";
      $fields = array();
      $raffle = CurlController::request($url, $method, $fields);


      if ($raffle->status == 200) {
        $raffle = $raffle->results[0];
        // Obtener número de WhatsApp desde el sorteo o usar uno por defecto
        if (empty($raffle->phone_raffle)) {
          echo '<div class="col-12 mx-1 mb-3 text-center alert alert-danger">ERROR: No se ha configurado el número de WhatsApp para esta rifa.</div>';
          return;
        }

        $phoneCleaned = str_replace(' ', '', trim($raffle->phone_raffle));

        if (!preg_match('/^0[89]\d{8}$/', $phoneCleaned)) {
          echo '<div class="col-12 mx-1 mb-3 text-center alert alert-danger">ERROR: El número configurado para WhatsApp no es válido. Debe comenzar con 09 u 08 y tener 10 dígitos.</div>';
          return;
        }

        $whatsappNumber = '+593' . substr($phoneCleaned, 1);


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

      // Si el método de pago es transferencia, redirigir a WhatsApp con mensaje
      if ($_POST["optradio"] == "transferencia") {

        $telefono = ltrim($whatsappNumber, '+');

        if (!$telefono) {
          echo '<div class="col-12 mx-1 mb-3 text-center alert alert-danger">ERROR: No se ha configurado el número de WhatsApp para este sorteo.</div>';
          return;
        }

        $nombre = TemplateController::capitalize($_POST["name"]);
        $apellido = TemplateController::capitalize($_POST["surname"]);
        $cliente = $nombre . " " . $apellido;

        $numerosTexto = $isDynamic ? "*Cantidad:* $total / Se generarán aleatorios" : "*Números solicitados:* " . $_POST["numbers"];
        $banco = '';
        if (!empty($_POST["bank_name"]) && !empty($_POST["bank_type"]) && !empty($_POST["bank_number"])) {
          $banco = "*Banco:* " . $_POST["bank_name"] . " (" . $_POST["bank_type"] . " " . $_POST["bank_number"] . ")";
        }

        $mensaje = rawurlencode(
          "Hola 👋, deseo participar en el sorteo y he reservado mis números.\n\n" .
          "💵 Realizaré el pago mediante depósito o transferencia a la siguiente cuenta:\n\n" .
          "{$banco}\n" .
          "Titular: " . $_ENV['TRANSFER_HOLDER'] . "\n" .
          "Cédula: " . $_ENV['TRANSFER_ID'] . "\n" .
          "Correo: " . $_ENV['TRANSFER_EMAIL'] . "\n\n" .
          "📋 *Mis datos personales:*\n" .
          "Nombre: $cliente\n" .
          "Correo: " . $_POST["email"] . "\n" .
          "WhatsApp: " . $_POST["whatsapp"] . "\n" .
          "Referencia de la orden: $ref\n" .
          "*Números solicitados:* " . ($isDynamic ? intval($_POST["numbers"]) : count($numbers)) . "\n" .
          "*Total a pagar:* $" . number_format($total, 2) . "\n\n" .
          "📎 Te enviaré el comprobante a continuación ✅"
        );

        $urlWhatsapp = "https://wa.me/" . $telefono . "?text=" . $mensaje;
        echo "<script>window.location = '$urlWhatsapp';</script>";
        return;
      }


      // Si el método de pago es PayPhone
      if ($_POST["optradio"] == "payphone") {

        $_SESSION["last_order_ref"] = $ref;

        $orderId = $createOrder->results->lastId;

        // Enviar correo al admin
        $subject = "[ProyectoEcuador] Pedido recibido por $" . number_format($total, 2);
        $email = $raffle->email_raffle ?? $_ENV['ADMIN_EMAIL'];
        $title = "[ProyectoEcuador] Pedido # " . $ref;
        $message = "
        <p>Nuevo pedido recibido de <strong>" . TemplateController::capitalize($_POST["name"]) . " " . TemplateController::capitalize($_POST["surname"]) . "</strong></p>
        <p>📞 Whatsapp: " . trim($_POST["whatsapp"]) . "<br>
        📧 Email: " . trim($_POST["email"]) . "</p>
        <p><strong>Número(s):</strong> <strong>" . $_POST["numbers"] . "</strong></p>
        <p>Método de pago: PayPhone</p>
        <p>Total: $" . number_format($total, 2) . "</p>";
        TemplateController::sendEmail($subject, $email, $title, $message, "https://proyectoecuador.com/ingresar");

        // Guardar datos  en localStorage para que el frontend pueda usarlos al renderizar el botón
        echo "<script>
        localStorage.setItem('pp_order_ref', '$ref');
        localStorage.setItem('pp_order_id', '$orderId');
        localStorage.setItem('pp_raffle_id', '{$raffle->id_raffle}');
        localStorage.setItem('pp_client_id', '$clientId');
        localStorage.setItem('pp_is_dynamic', '$isDynamic');
        localStorage.setItem('pp_numbers', '" . htmlspecialchars($_POST["numbers"]) . "');
        localStorage.setItem('pp_total', '" . number_format($total, 2, '.', '') . "');
        localStorage.setItem('pp_client_name', '" . TemplateController::capitalize($_POST["name"]) . "');
        localStorage.setItem('pp_client_surname', '" . TemplateController::capitalize($_POST["surname"]) . "');
        localStorage.setItem('pp_client_email', '" . trim($_POST["email"]) . "');
        localStorage.setItem('pp_client_phone', '" . trim($_POST["whatsapp"]) . "');

       window.location.href = '/payphone/confirmar?ref=$ref';
      </script>";
        return;
      }


    }
  }
}
?>