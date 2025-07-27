<!--=================================
MAIN
==================================-->
<?php

$totalSales = 0;
$method = "GET";
$fields = [];

$url = "sales?linkTo=id_raffle_sale,status_sale&equalTo=" . $raffle->id_raffle . ",PAID&select=number_sale";
$response = CurlController::request($url, $method, $fields);

if ($response->status == 200) {
    $totalSales = $response->total;
    $sales = $response->results;
} else {
    $sales = [];
}

// RANGO desde min_number y max_number 
$start = isset($raffle->min_number) ? intval($raffle->min_number) : 0;
$end = isset($raffle->max_number) ? intval($raffle->max_number) : 0;
$diff = ($end - $start) + 1;
if ($diff <= 0)
    $diff = 1;

$avance = ($totalSales / $diff) * 100;

?>

<div class="container-fluid p-0 position-relative" id="main">

    <?php
    include "views/modules/countdown/countdown.php";
    ?>
</div>