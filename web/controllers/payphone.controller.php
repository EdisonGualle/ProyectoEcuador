<?php

// Cargar variables del entorno desde .env
require_once __DIR__ . '../../extensions/env.loader.php';


require_once "curl.controller.php";

class PayPhoneController
{
  private $logFile;

  public function __construct()
  {
    $this->logFile = __DIR__ . "/log_payphone.txt";
  }

  private function log($message)
  {
    file_put_contents($this->logFile, $message . "\n", FILE_APPEND);
  }

  public function process()
  {
    $this->log("\n\n=== 🔁 VERIFICANDO PAYPHONE - " . date("Y-m-d H:i:s") . " ===");

    $raw = file_get_contents("php://input");
    $this->log("📥 RAW: $raw");

    $data = json_decode($raw, true);
    $id = $data["id"] ?? null;
    $ref = $data["ref"] ?? null;

    if (!$id || !$ref) {
      $this->log("❌ Faltan parámetros");
      return $this->response("error", "Faltan parámetros");
    }

    $confirm = $this->confirmPayphone($id, $ref);
    if (!$confirm)
      return;

    $order = $this->getOrderByRef($ref);
    if (!$order)
      return;

    $this->updateOrder($order, $id);
    $this->processSales($order);

    return $this->response("success", "Transacción confirmada y procesada correctamente", $order);
  }

  private function confirmPayphone($id, $ref)
  {
    $headers = [
      "Authorization: Bearer " . $_ENV['PAYPHONE_API_KEY'],
      "Content-Type: application/json"
    ];

    $payload = json_encode(["id" => (int) $id, "clientTxId" => $ref]);
    $this->log("📤 Enviando a PayPhone Confirm: $payload");

    $curl = curl_init();
    curl_setopt_array($curl, [
      CURLOPT_URL => "https://pay.payphonetodoesposible.com/api/button/V2/Confirm",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_POST => true,
      CURLOPT_POSTFIELDS => $payload,
      CURLOPT_HTTPHEADER => $headers
    ]);

    $response = curl_exec($curl);
    $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    $this->log("📬 Respuesta PayPhone [$httpcode]: $response");

    $data = json_decode($response, true);
    if (
      $httpcode !== 200 ||
      !isset($data["statusCode"], $data["transactionStatus"]) ||
      $data["statusCode"] != 3 ||
      $data["transactionStatus"] !== "Approved"
    ) {
      $this->log("❌ Transacción NO aprobada");
      $this->response("error", "Transacción no aprobada");
      return false;
    }

    return true;
  }

  private function getOrderByRef($ref)
  {
    $url = "relations?rel=orders,clients,raffles&type=order,client,raffle&linkTo=ref_order&equalTo=$ref";
    $res = CurlController::request($url, "GET", []);
    $this->log("🔎 Resultado de búsqueda por ref_order:\n" . json_encode($res, JSON_PRETTY_PRINT));

    if (!$res || $res->status != 200 || empty($res->results)) {
      $this->log("❌ Orden no encontrada");
      $this->response("error", "Orden no encontrada");
      return null;
    }

    return $res->results[0];
  }

  private function updateOrder($order, $id)
  {
    $urlPut = "orders?id={$order->id_order}&nameId=id_order&token=no&except=id_order";
    $payload = http_build_query([
      "status_order" => "PAID",
      "id_pay_order" => $id
    ]);

    $res = CurlController::request($urlPut, "PUT", $payload);
    $this->log("📤 PUT a orders:\n" . json_encode($res, JSON_PRETTY_PRINT));
  }

private function processSales($order)
{
    $type = $order->type_number_raffle;
    $this->log("🔁 Tipo de sorteo: $type");

    if ($type === "dinamico") {
        $generate = CurlController::request("sales?random=sales", "POST", [
            "id_raffle" => $order->id_raffle,
            "id_client" => $order->id_client,
            "id_order" => $order->id_order,
            "status_sale" => "PAID"
        ]);

        $this->log("🎲 Resultado generación random:\n" . json_encode($generate, JSON_PRETTY_PRINT));
        return;
    }

    // ESTÁTICO — buscar todas las ventas por id_order y marcarlas como PAID
    $errores = [];

    $ventas = CurlController::request("sales?linkTo=id_order_sale&equalTo={$order->id_order}", "GET", []);

    if (!$ventas || $ventas->status != 200 || empty($ventas->results)) {
        $this->log("⚠️ No se encontraron ventas relacionadas a la orden.");
        return;
    }

    foreach ($ventas->results as $venta) {
        $idSale = $venta->id_sale;

        $update = CurlController::request(
            "sales?id=$idSale&nameId=id_sale&token=no&except=id_sale",
            "PUT",
            http_build_query([
                "status_sale" => "PAID",
                "date_updated_sale" => date("Y-m-d H:i:s")
            ])
        );

        if (!$update || $update->status != 200) {
            $errores[] = $venta->number_sale;
        }
    }

    if (!empty($errores)) {
        $this->log("⚠️ Errores al actualizar ventas estáticas: " . implode(", ", $errores));
    } else {
        $this->log("✅ Ventas estáticas actualizadas correctamente.");
    }
}


  private function response($status, $message, $data = [])
  {
    echo json_encode([
      "status" => $status,
      "message" => $message,
      "data" => $data
    ]);
    exit;
  }
}

// Ejecutar directamente si es petición POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $controller = new PayPhoneController();
  $controller->process();
}
