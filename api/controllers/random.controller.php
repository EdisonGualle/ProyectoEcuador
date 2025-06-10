<?php

require_once "models/get.model.php";
require_once "models/post.model.php";

class RandomController
{
    static public function generateRandomSales($id_raffle, $id_client, $id_order, $qty)
    {
        // 1. Validar que la cantidad solicitada sea mayor que cero
        if ($qty <= 0) {
            echo json_encode([
                "status" => 400,
                "msg" => "La cantidad solicitada debe ser mayor que cero."
            ]);
            return;
        }

        // 2. Obtener la rifa
        $raffle = GetModel::getDataFilter("raffles", "*", "id_raffle", $id_raffle, null, null, null, null);
        if (empty($raffle)) {
            echo json_encode(["status" => 404, "msg" => "Rifa no encontrada"]);
            return;
        }

        $raffle = $raffle[0];

        $min = isset($raffle->min_number) ? intval($raffle->min_number) : 1;
        $max = isset($raffle->max_number) ? intval($raffle->max_number) : 100;

        if ($max < $min) {
            echo json_encode(["status" => 400, "msg" => "Rango inválido: el número máximo no puede ser menor que el mínimo."]);
            return;
        }

        // 3. Obtener números ya vendidos
        $sold = GetModel::getDataFilter("sales", "number_sale", "id_raffle_sale", $id_raffle, null, null, null, null);
        $soldNumbers = array_map(fn($s) => intval($s->number_sale), $sold);
        $availableNumbers = array_diff(range($min, $max), $soldNumbers);

        if (empty($availableNumbers)) {
            echo json_encode(["status" => 400, "msg" => "No hay números disponibles para asignar."]);
            return;
        }

        shuffle($availableNumbers);
        $numbersToAssign = array_slice($availableNumbers, 0, $qty);
        $inserted = 0;
        $savedNumbers = [];

        $status = (isset($_POST["status_sale"]) && strtoupper($_POST["status_sale"]) === "PAID") ? "PAID" : "PENDING";

        foreach ($numbersToAssign as $number) {
            $fields = [
                "id_raffle_sale" => $id_raffle,
                "id_client_sale" => $id_client,
                "id_order_sale" => $id_order,
                "number_sale" => $number,
                "status_sale" => $status,
                "date_created_sale" => date("Y-m-d")
            ];

            $res = PostModel::postData("sales", $fields);
            if (isset($res["status"]) && $res["status"] == 200) {
                $inserted++;
                $savedNumbers[] = $number;
            }
        }
        
        // Respuestas personalizadas
        if ($inserted === 0) {
            echo json_encode([
                "status" => 206,
                "msg" => "No se pudo insertar ningún número. Es posible que ya estén vendidos.",
                "numbers" => []
            ]);
        } elseif ($inserted < $qty) {
            echo json_encode([
                "status" => 206,
                "msg" => "Solo se generaron $inserted de los $qty número(s) solicitados por disponibilidad limitada.",
                "numbers" => $savedNumbers
            ]);
        } else {
            echo json_encode([
                "status" => 200,
                "msg" => "Se generaron $inserted número(s) correctamente.",
                "numbers" => $savedNumbers
            ]);
        }
    }
}
