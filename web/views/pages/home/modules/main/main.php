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

            <!-- FIN MODULO DE AVANCE -->

            <div class="row">
                <div class="offset-lg-1 col-lg-10">

                    <div class="row">
                        <div class="row justify-content-center text-center mb-5">
                            <div class="col-12 col-lg-8">
                                <h5 class="text-uppercase josefin-sans-700 t1">Necesita saber acerca de</h5>
                                <h1 class="text-uppercase josefin-sans-700 display-4">Cómo Jugar</h1>
                                <p class="h5 josefin-sans-700">¡Sigue estos 3 sencillos pasos!</p>
                            </div>
                        </div>
                        <div class="row justify-content-center gy-4">
                            <?php
                            $url = "howtoplaysections?orderBy=step_howtoplaysection&orderMode=ASC";
                            $method = "GET";
                            $fields = array();
                            $howToPlay = CurlController::request($url, $method, $fields);

                            if ($howToPlay->status == 200):
                                foreach ($howToPlay->results as $index => $item):
                                    $background = "/views/assets/img/card-bg-" . ($index + 1) . ".jpg";
                                    $icon = "/views/assets/img/" . ($index + 1) . ".png";
                                    ?>
                                    <div class="col-12 col-md-4">
                                        <div class="card colorImage h-100 border-0 shadow-sm"
                                            style="background:url('<?php echo $background ?>'); background-size: cover; background-position: center center;">
                                            <div class="card-body text-center text-white px-3">
                                                <figure class="rounded-circle c1 mx-auto mb-3">
                                                    <img src="<?php echo $icon ?>" class="img-fluid">
                                                </figure>
                                                <p class="h5 josefin-sans-700 mb-2">
                                                    <?php echo $item->step_howtoplaysection . '.' . strtoupper($item->title_howtoplaysection); ?>
                                                </p>
                                                <p class="small josefin-sans-700"><?php echo $item->description_howtoplaysection; ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                endforeach;
                            endif;
                            ?>
                        </div>
                    </div>

                    <?php if ($raffle->type_number_raffle == "dinamico"): ?>
                        <h1 class="josefin-sans-700 text-uppercase text-center mt-5 pt-lg-5">
                            PARTICIPA FÁCIL Y RÁPIDO | $<?= number_format(urldecode($raffle->price_raffle), 2) ?> USD C/U
                        </h1>
                    <?php else: ?>
                        <h1 class="josefin-sans-700 text-uppercase text-center mt-5 pt-lg-5">
                            ELIGE TU(S) NÚMERO(S) | $<?= number_format(urldecode($raffle->price_raffle), 2) ?> USD C/U
                        </h1>
                    <?php endif; ?>

                    <?php if ($raffle->type_number_raffle == "dinamico"): ?>
                        <div class="text-center position-relative bg p-4 p-lg-5 my-5 rounded"
                            style="z-index:1; max-width: 700px; margin-left: auto; margin-right: auto;">

                            <h2 class="text-uppercase josefin-sans-700 mb-3">Compra rápida de números</h2>
                            <p class="josefin-sans-700 mb-4">Ingresa la cantidad de números que deseas adquirir y continúa con
                                la compra</p>

                            <form method="GET" action="/checkout" class="needs-validation" novalidate>

                                <!-- ID de la rifa -->
                                <input type="hidden" name="raffle" value="<?= $raffle->id_raffle ?>">

                                <!-- Campo cantidad -->
                                <div class="form-group mb-4">
                                    <input type="number" name="numbers" min="1" max="<?= $diff - $totalSales ?>"
                                        class="form-control form-control-lg text-center py-3 rounded" style="font-size: 1.3rem;"
                                        placeholder="Ej: 3" required>
                                    <div class="invalid-feedback">Por favor ingresa un número válido entre 1 y
                                        <?= $diff - $totalSales ?>.
                                    </div>
                                </div>

                                <!-- Botón continuar -->
                                <div class="form-group">
                                    <button type="submit" class="btn btn-lg w-100 b1 text-white py-3 border-0 rounded">
                                        COMPRAR NÚMERO(s)
                                    </button>
                                </div>
                            </form>
                        </div>

                        <script>
                            document.addEventListener('DOMContentLoaded', function () {
                                const forms = document.querySelectorAll('.needs-validation');
                                Array.from(forms).forEach(form => {
                                    form.addEventListener('submit', function (event) {
                                        if (!form.checkValidity()) {
                                            event.preventDefault();
                                            event.stopPropagation();
                                        }
                                        form.classList.add('was-validated');
                                    }, false);
                                });
                            });
                        </script>
                    <?php else: ?>


                        <div class="text-center position-relative" style="z-index:1">
                            <div class="d-flex flex-wrap w-100 justify-content-center bg p-2 p-lg-3 py-lg-5 rounded">

                                <?php
                                $sold = 0;
                                for ($i = $start; $i <= $end; $i++):
                                    $num = str_pad($i, strlen((string) $end), "0", STR_PAD_LEFT);
                                    $isSold = false;
                                    foreach ($sales as $s) {
                                        if ($s->number_sale == $num) {
                                            $isSold = true;
                                            break;
                                        }
                                    }
                                    ?>

                                    <?php if ($isSold): ?>
                                        <div class="h3 text-center numbers sold rounded-circle m-1" number="<?= $num ?>"
                                            style="cursor:pointer">
                                            <span class="p-2"><s><?= $num ?></s></span>
                                        </div>
                                    <?php else: ?>
                                        <div class="h3 text-center numbers numbersClick rounded-circle m-1" item="0"
                                            number="<?= $num ?>" style="cursor:pointer">
                                            <span class="p-2"><?= $num ?></span>
                                        </div>
                                    <?php endif; ?>

                                <?php endfor; ?>

                            </div>

                            <button
                                class="my-4 btn btn-default btn-lg btn-block w-100 p-3 border rounded text-white buyNumbers b1 border-0">
                                COMPRAR NÚMERO(s)
                            </button>
                        </div>
                    <?php endif; ?>


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