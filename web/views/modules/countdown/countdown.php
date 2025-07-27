<?php
// ===================== LÓGICA DEL PORCENTAJE =====================
$percent = 0;

if (isset($raffle->type_progressbar_raffle) && $raffle->type_progressbar_raffle === 'manual') {
    $percent = (int) $raffle->manual_progresspercent_raffle;
    $percent = max(0, min(100, $percent));
} else {
    $totalSales = $totalSales ?? 0;
    $diff = $diff ?? 1;
    $percent = ceil($totalSales * 100 / $diff);
}

// ===================== COLORES DE PLANTILLA =====================
$colorText   = urldecode($template->color0_template);
$colorHover  = urldecode($template->color4_template);
$colorCard   = urldecode($template->color3_template);

// ===================== FUNCIÓN PARA PROCESAR URLs DE VIDEO =====================
function processVideoUrl($url)
{
    if (empty($url)) return null;

    // Detectar y convertir URLs de YouTube
    if (mb_strpos($url, 'youtube.com/watch') !== false) {
        $videoId = null;
        parse_str(parse_url($url, PHP_URL_QUERY), $params);
        if (isset($params['v'])) {
            $videoId = $params['v'];
        }
        return $videoId ? "https://www.youtube.com/embed/$videoId" : null;
    }

    // Detectar URLs de YouTube cortas
    if (mb_strpos($url, 'youtu.be/') !== false) {
        $videoId = basename(parse_url($url, PHP_URL_PATH));
        return "https://www.youtube.com/embed/$videoId";
    }

    // Detectar URLs de Facebook
    if (mb_strpos($url, 'facebook.com') !== false) {
        return $url;
    }

    return $url;
}

$processedVideoUrl = processVideoUrl($raffle->video_live_raffle);
$isFacebookVideo = mb_strpos($raffle->video_live_raffle, 'facebook.com') !== false;
?>

<!-- ===================== EVENTO EN VIVO O GANADOR ===================== -->
<?php if ($raffle->win_raffle > 0 && $raffle->number_win_raffle > 0): ?>
    <div class="text-center position-relative py-3 py-md-5">
        <div class="container">
            <div class="display-4 display-md-1 josefin-sans-700 mb-3">¡Feliz Ganador!</div>

            <?php
            $url = "clients?linkTo=id_client&equalTo=" . $raffle->win_raffle;
            $winClient = CurlController::request($url, $method, $fields)->results[0];
            ?>

            <div class="d-flex flex-column flex-md-row align-items-center justify-content-center flex-wrap w-100 p-2 p-md-3 rounded">
                <div class="h3 h2-md mb-2 mb-md-0 pe-md-2"><?= $winClient->name_client . " " . $winClient->surname_client ?> con el número </div>
                <div class="h4 h3-md text-center numbers rounded-circle m-1"><span class="p-2 p-md-3"><?= $raffle->number_win_raffle ?></span></div>
            </div>
            <p class="my-2 my-md-3 lead">¡Gracias por participar, nos vemos en el próximo sorteo!</p>
        </div>
    </div>

<?php else: ?>
    <div class="text-center position-relative py-3 py-md-5">
        <div class="countdown container">

            <?php if ($raffle->end_date_raffle > date("Y-m-d H:i:s")): ?>

                <?php if (
                    (empty($raffle->win_raffle) || empty($raffle->number_win_raffle)) &&
                    ($raffle->end_date_raffle > date("Y-m-d H:i:s"))
                ): ?>
                    <div class="container d-flex justify-content-center pt-2 pt-md-4 pb-0">
                        <div class="col-12 col-md-10 col-lg-9">
                            <div class="card p-3 p-md-4 rounded-4 text-center" style="background: transparent; border: none;">

                                <h3 class="h4 h3-md mb-2 mb-md-3 text-uppercase fw-bold" style="letter-spacing: 0.5px; color: <?= $colorText ?>;">
                                    Avance del Sorteo
                                </h3>

                                <div class="progress mb-2 mb-md-3 mx-auto"
                                    style="height: 20px; border-radius: 50px; overflow: hidden; width: 90%; max-width: 600px;">
                                    <div class="progress-bar bg-success progress-bar-striped progress-bar-animated text-white fw-bold"
                                        role="progressbar"
                                        style="width: <?= $percent ?>%;"
                                        aria-valuenow="<?= $percent ?>" aria-valuemin="0" aria-valuemax="100">
                                        <?= $percent ?>%
                                    </div>
                                </div>

                                <?php if (!empty($raffle->description_targetprogress_raffle)): ?>
                                    <p class="mt-1 mt-md-2 mb-1 px-2" style="font-size: 0.9rem; font-size-md: 0.95rem; color: <?= $colorText ?>;">
                                        <?= urldecode($raffle->description_targetprogress_raffle); ?>
                                    </p>
                                <?php endif; ?>

                            </div>
                        </div>
                    </div>
                <?php endif; ?>

            <?php else: ?>

                <h3 class="display-5 display-md-4 fw-bold text-white mb-3 mb-md-4 mt-3 mt-md-5">¡Sorteo en Vivo!</h3>

                <!-- CONTENEDOR DE VIDEO MEJORADO -->
                <div class="container mt-3 mt-md-4 mb-4 mb-md-5 px-0 px-md-3" style="z-index:1; position:relative;">
                    <?php if ($isFacebookVideo): ?>
                        <!-- Video de Facebook Responsivo -->
                        <div class="row justify-content-center">
                            <div class="col-12 col-md-10 col-lg-8">
                                <div class="fb-video-container bg-dark rounded-3 shadow-lg overflow-hidden">
                                    <div class="ratio ratio-16x9">
                                        <div id="fb-root"></div>
                                        <script async defer crossorigin="anonymous"
                                            src="https://connect.facebook.net/es_LA/sdk.js#xfbml=1&version=v18.0"></script>
                                        <div class="fb-video"
                                            data-href="<?= $raffle->video_live_raffle ?>"
                                            data-width="auto"
                                            data-show-text="false"
                                            data-allowfullscreen="true"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <!-- Video de YouTube/otros Responsivo -->
                        <div class="row justify-content-center">
                            <div class="col-12 col-md-10 col-lg-8">
                                <div class="video-container bg-dark rounded-3 shadow-lg overflow-hidden">
                                    <div class="ratio ratio-16x9">
                                        <iframe class="w-100 h-100"
                                            src="<?= $processedVideoUrl ?>"
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                            referrerpolicy="strict-origin-when-cross-origin"
                                            allowfullscreen>
                                        </iframe>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Información del Sorteo -->
                    <div class="text-center mt-3 mt-md-4 px-2">

                        <p class="text-white-50 mb-2 fs-6 fs-md-5"><?= urldecode($raffle->description_targetprogress_raffle); ?></p>

                        <p class="text-white-50 fs-6 fs-md-5">
                            Sorteo <?= isset($raffle->end_date_raffle) ? TemplateController::formatDate(4, urldecode($raffle->end_date_raffle)) : 'fecha por definir' ?>
                        </p>

                        <p class="text-white-50 small small-md mt-2 mt-md-3">
                            Si no puedes ver el video aquí,
                            <a href="<?= $raffle->video_live_raffle ?>"
                                target="_blank"
                                class="fw-bold text-decoration-underline text-warning">
                                haz clic aquí para ver el evento
                            </a>.
                        </p>
                    </div>
                </div>

            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>

<style>
    /* Estilos para el contenedor de video */
    .video-container,
    .fb-video-container {
        position: relative;
        background: #000;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    /* Mantener proporción 16:9 */
    .ratio-16x9 {
        --bs-aspect-ratio: 56.25%;
    }

    /* Iframe del video */
    .video-container iframe {
        border: none;
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }

    /* Contenedor de Facebook Video */
    .fb-video {
        width: 100% !important;
        min-height: 250px;
    }

    .fb-video>span {
        width: 100% !important;
    }

    .fb-video iframe {
        width: 100% !important;
        min-height: 250px;
    }

    /* Estilos para el texto del ganador */
    .numbers {
        background-color: <?= $colorHover ?>;
        color: white;
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Tipografía responsiva */
    .display-md-1 {
        font-size: calc(1.625rem + 4.5vw);
    }

    .display-md-4 {
        font-size: calc(1.475rem + 2.7vw);
    }

    .display-md-5 {
        font-size: calc(1.425rem + 2.1vw);
    }

    .h2-md {
        font-size: calc(1.3rem + 0.6vw);
    }

    .h3-md {
        font-size: calc(1.275rem + 0.3vw);
    }

    .h4-md {
        font-size: 1.25rem;
    }

    /* Ajustes para móviles */
    @media (max-width: 767.98px) {

        .video-container,
        .fb-video-container {
            border-radius: 0.5rem !important;
        }

        .fb-video,
        .fb-video iframe {
            min-height: 200px;
        }

        .numbers {
            width: 50px;
            height: 50px;
        }
    }

    /* Ajustes para tablets y desktop */
    @media (min-width: 768px) {

        .video-container,
        .fb-video-container {
            border-radius: 0.75rem !important;
        }

        .fb-video,
        .fb-video iframe {
            min-height: 400px;
        }

        .display-md-1 {
            font-size: 5rem;
        }

        .display-md-4 {
            font-size: 2.5rem;
        }

        .display-md-5 {
            font-size: 2rem;
        }

        .h2-md {
            font-size: 2rem;
        }

        .h3-md {
            font-size: 1.75rem;
        }
    }

    /* Fallback para video no disponible */
    .video-fallback {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 200px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 15px;
    }

    @media (min-width: 768px) {
        .video-fallback {
            min-height: 420px;
        }
    }
</style>