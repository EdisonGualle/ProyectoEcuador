<!--=================================
HERO
==================================-->

<?php
$numbersString = $_GET["numbers"] ?? '';
$isDynamic = is_numeric($numbersString);
?>

<div class="container-fluid p-0" id="heroCheckout">

    <div class="container-fluid">

        <div class="container">

            <div class="row row-cols-1 pt-4">

                <div class="offset-3 col-6 textAlign text-dark text-center">

                    <h1 class="josefin-sans-700 text-uppercase py-2 px-1 b1 pt-3 mb-0 mb-lg-3 rounded">Te falta un paso
                    </h1>

                    <h2 class="p-2">
                        <?php if ($isDynamic): ?>
                            Estás a un paso de activar tu jugada automática
                        <?php else: ?>
                            Para obtener tus números ganadores
                        <?php endif; ?>
                    </h2>

                    <div class="text-center position-relative" style="z-index:1">

                        <?php
                        $numbersString = $_GET["numbers"] ?? '';
                        $isDynamic = is_numeric($numbersString);

                        $numbers = $isDynamic ? [] : explode(",", $numbersString);

                        ?>

                        <div class="d-flex flex-wrap w-100 justify-content-center p-2 rounded">

                            <?php if ($isDynamic): ?>
                                <div class="d-flex flex-column align-items-center justify-content-center">
                                    <div class="d-flex flex-wrap justify-content-center p-2 rounded">
                                        <?php for ($i = 0; $i < intval($numbersString); $i++): ?>
                                            <div class="h3 text-center numbers rounded-circle m-1" item="0">
                                                <span class="p-2">?</span>
                                            </div>
                                        <?php endfor; ?>
                                    </div>
                                    <p class="mt-3 text-muted h6 text-center">
                                        Los números se generarán automáticamente después del pago.
                                    </p>
                                </div>
                            <?php else: ?>
                                <?php
                                $isValid = true;
                                foreach ($numbers as $key => $value) {
                                    if (!is_numeric($value)) {
                                        echo "<script>window.location = '/';</script>";
                                        $isValid = false;
                                        break;
                                    }
                                }
                                ?>
                                <?php if ($isValid): ?>
                                    <?php foreach ($numbers as $key => $value): ?>
                                        <div class="h3 text-center numbers rounded-circle m-1" item="0" number="<?= $value ?>">
                                            <span class="p-2"><?= $value ?></span>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>




                            <?php endif; ?>


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