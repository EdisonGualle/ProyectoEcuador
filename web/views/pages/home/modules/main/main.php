<?php 

$totalSales = 0;
$method = "GET";
$fields = [];

$url = "sales?linkTo=id_raffle_sale&equalTo=".$raffle->id_raffle."&select=number_sale";
$response = CurlController::request($url, $method, $fields);

if($response->status == 200){
    $totalSales = $response->total;
    $sales = $response->results;
} else {
    $sales = [];
}

// RANGO desde min_number y max_number 
$start = isset($raffle->min_number) ? intval($raffle->min_number) : 0;
$end = isset($raffle->max_number) ? intval($raffle->max_number) : 0;
$diff = ($end - $start) + 1;
if ($diff <= 0) $diff = 1;

$avance = ($totalSales / $diff) * 100;

?>

<!--=================================
MAIN
==================================-->

<div class="container-fluid p-0 position-relative" id="main">

    <figure class="position-absolute colorImage" style="top:0;left:0;width:100%">
        <img src="/views/assets/img/contest-bg.png" class="img-fluid w-100">
    </figure>

    <?php include "views/modules/countdown/countdown.php"; ?>

    <?php if ($raffle->end_date_raffle > date("Y-m-d H:i:s")): ?>

        <div class="container mt-3 pb-5 mt-lg-0 px-3 px-lg-5 position-relative">

            <!-- MODULO DE AVANCE -->
            <div class="row mb-4">
                <div class="col-12 col-md-10 offset-md-1">
                    <h2 class="text-uppercase text-center josefin-sans-700 mb-3">
                        Avance de la Rifa
                    </h2>

                    <div class="progress rounded-pill" style="height: 30px;">
                        <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" 
                            role="progressbar" 
                            style="width: <?= number_format($avance, 2) ?>%;" 
                            aria-valuenow="<?= number_format($avance, 2) ?>" 
                            aria-valuemin="0" 
                            aria-valuemax="100">
                            <?= number_format($avance, 2) ?>%
                        </div>
                    </div>

                    <p class="text-center mt-2 mb-0 josefin-sans-700 h6">
                        Se han vendido <?= $totalSales ?> de <?= $diff ?> números
                    </p>

                    <?php if ($end <= $start): ?>
                        <p class="text-danger text-center mt-2">
                            ⚠️ Error: el rango de números (mínimo y máximo) está mal configurado.
                        </p>
                    <?php endif; ?>
                </div>
            </div>
            <!-- FIN MODULO DE AVANCE -->

            <div class="row">
                <div class="offset-lg-1 col-lg-10">

                    <div class="row">
                        <div class="col-12 col-lg-6 mt-5 mt-lg-0">
                            <h5 class="text-uppercase josefin-sans-700 t1">Necesita saber acerca de</h5> 
                            <h1 class="text-uppercase josefin-sans-700 display-4">Cómo Jugar</h1> 
                            <p class="h5 josefin-sans-700">¡Sigue estos 3 sencillos pasos!</p> 
                        </div>

                        <div class="col-12 col-lg-2 mb-2 mb-lg-0">
                            <div class="card colorImage" style="background:url('/views/assets/img/card-bg-1.jpg'); background-size: cover; background-position: center center;">
                                <div class="card-body text-center">
                                    <figure class="rounded-circle c1 mx-auto">
                                        <img src="/views/assets/img/1.png" class="img-fluid">
                                    </figure>
                                    <p class="h5 josefin-sans-700">1.ELIGE<br><small>Tu número ganador</small></p>
                                </div>
                            </div> 
                        </div>

                        <div class="col-12 col-lg-2 mb-2 mb-lg-0">
                            <div class="card colorImage" style="background:url('/views/assets/img/card-bg-2.jpg'); background-size: cover; background-position: center center;">
                                <div class="card-body text-center">
                                    <figure class="rounded-circle c1 mx-auto">
                                        <img src="/views/assets/img/2.png" class="img-fluid">
                                    </figure>
                                    <p class="h5 josefin-sans-700">2.COMPRA<br><small>Completando los datos</small></p>
                                </div>
                            </div> 
                        </div>

                        <div class="col-12 col-lg-2 mb-2 mb-lg-0">
                            <div class="card colorImage" style="background:url('/views/assets/img/card-bg-3.jpg'); background-size: cover; background-position: center center;">
                                <div class="card-body text-center">
                                    <figure class="rounded-circle c1 mx-auto">
                                        <img src="/views/assets/img/3.png" class="img-fluid">
                                    </figure>
                                    <p class="h5 josefin-sans-700">3.GANA<br><small>Ya casi estás ahí</small></p>
                                </div>
                            </div> 
                        </div>
                    </div>

                    <h1 class="josefin-sans-700 text-uppercase text-center mt-5 pt-lg-5">
                        ELIGE TU(s) NÚMERO(s) | $<?= number_format(urldecode($raffle->price_raffle), 2) ?> USD C/U
                    </h1>

                    <div class="text-center position-relative" style="z-index:1">
                        <div class="d-flex flex-wrap w-100 justify-content-center bg p-2 p-lg-3 py-lg-5 rounded">

                            <?php 
                            $sold = 0;
                            for ($i = $start; $i <= $end; $i++): 
                                $num = str_pad($i, strlen((string)$end), "0", STR_PAD_LEFT);
                                $isSold = false;
                                foreach ($sales as $s) {
                                    if ($s->number_sale == $num) {
                                        $isSold = true;
                                        break;
                                    }
                                }
                            ?>

                                <?php if ($isSold): ?>
                                    <div class="h3 text-center numbers sold rounded-circle m-1" number="<?= $num ?>" style="cursor:pointer">
                                        <span class="p-2"><s><?= $num ?></s></span>
                                    </div>
                                <?php else: ?>
                                    <div class="h3 text-center numbers numbersClick rounded-circle m-1" item="0" number="<?= $num ?>" style="cursor:pointer">
                                        <span class="p-2"><?= $num ?></span>
                                    </div>
                                <?php endif; ?>

                            <?php endfor; ?>

                        </div>

                        <button class="my-4 btn btn-default btn-lg btn-block w-100 p-3 border rounded text-white buyNumbers b1 border-0">
                            COMPRAR NÚMERO(s)
                        </button>
                    </div>

                </div>
            </div>

        </div>

        <svg class="position-absolute" style="bottom:0px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
            <path fill="<?= $template == null ? '#360254' : urldecode($template->color2_template) ?>" fill-opacity="1" 
                d="M0,160L120,186.7C240,213,480,267,720,256C960,245,1200,171,1320,133.3L1440,96L1440,320L1320,320C1200,320,960,320,720,320C480,320,240,320,120,320L0,320Z">
            </path>
        </svg>

    <?php endif; ?>

</div>
