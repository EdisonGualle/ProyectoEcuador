<?php
/** @var stdClass $order */
if (!isset($order)) {
    echo "<div class='alert alert-danger text-center'>❌ No se encontró la información de la orden.</div>";
    return;
}
?>

<!--=================================
HERO
==================================-->

<div class="container-fluid p-0" id="heroCheckout">

    <div class="container-fluid">

        <div class="container">

            <div class="row row-cols-1 pt-4">

                <div class="offset-3 col-6 textAlign text-dark text-center">

                    <?php if ($status == "PAID"): ?>

                        <h1 class="josefin-sans-700 text-uppercase py-2 px-1 b1 pt-3 mb-0 mb-lg-3 rounded">Gracias por tu
                            compra</h1>

                    <?php else: ?>

                        <h1 class="josefin-sans-700 text-uppercase py-2 px-1 b1 pt-3 mb-0 mb-lg-3 rounded">Estamos
                            procesando y validando tu pago</h1>

                    <?php endif ?>



                    <h2 class="p-2">
                        <?php if ($order->type_number_raffle === "dinamico"): ?>
                            ¡Estos son tus números de la suerte! Generados especialmente para ti.
                        <?php else: ?>
                            Estos son tus números elegidos 🎯
                        <?php endif; ?>
                    </h2>


                    <div class="text-center position-relative" style="z-index:1">
                        <div class="d-flex flex-wrap w-100 justify-content-center p-2 rounded">

                            <?php
                            $urlSales = "sales?linkTo=id_order_sale&equalTo=" . $order->id_order . "&select=number_sale";
                            $responseSales = CurlController::request($urlSales, "GET", []);

                            $numbers = ($responseSales->status == 200)
                                ? array_map(fn($sale) => $sale->number_sale, $responseSales->results)
                                : [];

                            foreach ($numbers as $key => $value):
                                ?>

                                <div class="h3 text-center numbers rounded-circle m-1" item="0"
                                    number="<?php echo $value ?>">
                                    <span class="p-2"><?php echo $value ?></span>
                                </div>

                            <?php endforeach ?>

                        </div>


                    </div>

                    <div class="col p-1 mb-5 py-lg-5 px-lg-5 position-relative">

                        <?php

                        include "views/modules/product/product.php";

                        ?>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="position-relative">

        <?php

        include "views/modules/svgs/svgs.php";

        ?>

    </div>

</div>