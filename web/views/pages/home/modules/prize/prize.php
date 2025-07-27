<?php
// ===================== CARGA DE GALERÍA =====================
$url = "galleries?linkTo=id_product_gallery&equalTo=" . $raffle->id_product;
$galleries = CurlController::request($url, $method, $fields);
$galleries = ($galleries->status == 200) ? $galleries->results : [];

// ===================== CÁLCULO DE % VENDIDO =====================
$percent = 0;
if (isset($sales) && !empty($sales)) {
    $percent = ceil($totalSales * 100 / $diff);
}

$colorCard = isset($template) ? urldecode($template->color3_template) : '';

?>

<!--=================================
PRIZE
==================================-->
<div class="container-fluid p-0 position-relative" id="prize">

    <h1 class="display-4 josefin-sans-700 text-uppercase text-center">CONOCE EL PREMIO</h1>

    <div class="container">

        <?php if (!empty($galleries)): ?>
            <!-- Carousel -->
            <div id="demo" class="carousel slide mx-auto" data-bs-ride="carousel" style="max-width: 720px;">
                <div class="carousel-indicators">
                    <?php foreach ($galleries as $key => $value): ?>
                        <button type="button" data-bs-target="#demo" data-bs-slide-to="<?= $key ?>" <?= ($key == 0) ? 'class="active"' : '' ?>></button>
                    <?php endforeach ?>
                </div>

                <div class="carousel-inner rounded">
                    <?php foreach ($galleries as $key => $value): ?>
                        <div class="carousel-item <?= ($key == 0) ? 'active' : '' ?>">
                            <img src="<?= urldecode($value->img_gallery) ?>" alt="<?= htmlspecialchars(urldecode($value->title_gallery)) ?>"
                                class="d-block w-100"
                                style="max-height: 500px; object-fit: cover; border-radius: 10px;">
                            <div class="carousel-caption">
                                <h3><?= urldecode($value->title_gallery) ?></h3>
                                <p><?= urldecode($value->description_gallery) ?></p>
                            </div>
                        </div>
                    <?php endforeach ?>
                </div>

                <button class="carousel-control-prev" type="button" data-bs-target="#demo" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#demo" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </button>
            </div>
        <?php endif ?>

        <div class="row pt-5 py-lg-5">
            <div class="col-12 col-lg-8">
                <h5 class="text-uppercase t1">Participa ahora para tener la oportunidad de ganar</h5>
                <h1 class="text-uppercase josefin-sans-700 display-4"><?= urldecode($raffle->tittle) ?></h1>
                <p class="h5">
                    Sorteo <?= TemplateController::formatDate(4, urldecode($raffle->end_date_raffle)) ?>
                    <small><span class="px-3">|</span><?= urldecode($raffle->location_raffle) ?></small>
                </p>

                <hr style="border:1px solid #fff">

                <h3>Descripción general del premio</h3>
                <p><?= urldecode($raffle->description_product) ?></p>
            </div>

            <div class="col-12 col-lg-4 pt-2">
                <!-- Tarjeta de consulta -->
                <div class="card p-4 rounded text-center shadow-sm w-100 text-white"
                    style="max-width: 500px; margin-left: auto; background: <?= $colorCard ?>;" id="card-result">
                    <h4 class="text-uppercase fw-bold mb-2" style="letter-spacing: 1px;">
                        Consulta tus <span style="font-style: italic;">números</span>
                    </h4>
                    <p class="mb-3">
                        ¿Ya hiciste tu compra?<br>
                        Consulta tus números ingresando aquí tu correo electrónico.
                    </p>

                    <form method="GET" action="consultados">
                        <input type="email" name="email" class="form-control mb-3 text-center" placeholder="Correo Electrónico" required>
                        <button type="submit" class="btn btn-outline-light btn-lg w-100 rounded-pill">CONSULTAR</button>
                    </form>
                </div>

            </div>
        </div>

    </div>
</div>