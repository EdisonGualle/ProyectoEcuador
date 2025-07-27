<?php
$status = "";
$numbers = [];
$client = null;

if (isset($_GET['email'])) {
    $email = trim($_GET['email']);

    // Buscar cliente por correo
    $clientUrl = "clients?linkTo=email_client&equalTo=" . urlencode($email) . "&select=id_client,name_client,surname_client";
    $clientRes = CurlController::request($clientUrl, "GET", []);

    if ($clientRes->status === 200 && isset($clientRes->results[0]->id_client)) {
        $client = $clientRes->results[0];
        $idClient = $client->id_client;

        // Obtener sorteo activo
        $raffleUrl = "raffles?linkTo=status_raffle&equalTo=1&orderBy=id_raffle&orderMode=ASC";
        $raffleRes = CurlController::request($raffleUrl, "GET", []);
        if ($raffleRes->status === 200) {
            $raffle = $raffleRes->results[0];

            // Buscar números asignados a este cliente en el sorteo activo
            $salesUrl = "sales?linkTo=id_client_sale,id_raffle_sale,status_sale&equalTo=$idClient,$raffle->id_raffle,PAID&select=number_sale";
            $salesRes = CurlController::request($salesUrl, "GET", []);

            if ($salesRes->status === 200) {
                $numbers = array_column($salesRes->results, "number_sale");
            }
        }
    }
}

include "modules/hero/hero.php";
include "modules/main/main.php";
