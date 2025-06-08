<?php

require_once "models/get.model.php";
require_once "models/post.model.php";

class RandomController {

    static public function generateRandomSales($id_raffle, $id_client, $id_order, $qty) {

        // 1. Obtener la rifa
        $raffle = GetModel::getDataFilter("raffles", "*", "id_raffle", $id_raffle, null, null, null, null);
        if (empty($raffle)) {
            echo json_encode(["status" => 404, "msg" => "Rifa no encontrada"]);
            return;
        }

        $raffle = $raffle[0];
        $min = isset($raffle->min_number_random) ? intval($raffle->min_number_random) : 1;
        $max = isset($raffle->max_number_random) ? intval($raffle->max_number_random) : 100;

        // 2. Números ya vendidos
        $sold = GetModel::getDataFilter("sales", "number_sale", "id_raffle_sale", $id_raffle, null, null, null, null);
        $soldNumbers = array_map(fn($s) => intval($s->number_sale), $sold);
        $availableNumbers = array_diff(range($min, $max), $soldNumbers);

        if (empty($availableNumbers)) {
            echo json_encode(["status" => 400, "msg" => "No hay números disponibles en la rifa"]);
            return;
        }

        shuffle($availableNumbers);
        $numbersToAssign = array_slice($availableNumbers, 0, $qty);
        $inserted = 0;

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
            }
        }

        if ($inserted === 0) {
            echo json_encode([
                "status" => 206,
                "msg" => "No se pudo insertar ningún número, intenta nuevamente.",
                "numbers" => []
            ]);
        } else if ($inserted < $qty) {
            echo json_encode([
                "status" => 206,
                "msg" => "Solo se generaron $inserted de los $qty número(s) solicitados por falta de disponibilidad.",
                "numbers" => $numbersToAssign
            ]);
        } else {
            echo json_encode([
                "status" => 200,
                "msg" => "Se generaron $inserted número(s) correctamente.",
                "numbers" => $numbersToAssign
            ]);
        }
    }
}
