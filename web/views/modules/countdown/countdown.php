<?php
// Valor estático del avance de la rifa (50%)
$percent = 59;

// Colores definidos desde base de datos
$colorText   = urldecode($template->color0_template); // color del texto y bordes
$colorHover  = urldecode($template->color4_template); // color hover para botones (no se usa aquí)
$colorCard   = urldecode($template->color3_template); // fondo de la card (tampoco se usa)
$colorBarra2 = 'black'; // color personalizado de la barra de progreso (no usado si usamos Bootstrap color)
?>

<!-- Contenedor principal centrado -->
<div class="container d-flex justify-content-center py-5">
    <div class="col-12 col-md-10 col-lg-9">

        <!-- Tarjeta con fondo transparente -->
        <div class="card p-5 rounded-4 text-center"
             style="background: transparent; border: none;">

            <!-- Título del avance -->
            <h3 class="mb-4 text-uppercase fw-bold"
                style="letter-spacing: 0.5px; color: <?php echo $colorText ?>;">
                Avance del Sorteo
            </h3>

            <!-- Barra de progreso estática al 50% -->
            <div class="progress mb-3 mx-auto"
                 style="height: 30px; border-radius: 50px; overflow: hidden; width: 90%;">
                <div class="progress-bar bg-success progress-bar-striped progress-bar-animated text-white fw-bold"
                     role="progressbar"
                     style="width: <?php echo $percent; ?>%;"
                     aria-valuenow="<?php echo $percent; ?>" aria-valuemin="0" aria-valuemax="100">
                    <?php echo $percent ?>%
                </div>
            </div>

            <!-- Texto descriptivo desde la base de datos (si existe) -->
            <?php if (!empty($raffle->description_targetprogress_raffle)): ?>
                <p class="mt-4 mb-0 px-2"
                   style="font-size: 1rem; color: <?php echo $colorText ?>;">
                    <?php echo urldecode($raffle->description_targetprogress_raffle); ?>
                </p>
            <?php endif; ?>

        </div>

    </div>
</div>

