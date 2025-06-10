<!-- HERO Checkout personalizado para políticas -->
<div class="container-fluid p-0" id="heroCheckout">

    <!-- Fondo degradado celeste -->
    <div class="container-fluid py-5">

        <!-- Tarjeta centrada y separada -->
        <div class="container d-flex justify-content-center align-items-center" style="padding-top: 0px; padding-bottom: 200px;">
            <div class="rounded-4 shadow-xl p-4 px-5 text-white"
                style="max-width: 960px; background: <?php echo $template->color1_template; ?>; z-index: 2; position: relative; border: 1px solid rgba(255,255,255,0.2); backdrop-filter: blur(3px);">

                <!-- Título destacado centrado -->
                <h1 class="btn btn-default btn-lg rounded pt-3 text-uppercase b1 border-0 text-white d-block mx-auto text-center mb-4">
                    Contáctenos
                </h1>

                <!-- Contacto -->
                <div class="text-center">
                    <!-- Información principal de contacto -->
                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <h5 class="fw-bold mb-3">
                                    <i class="bi bi-envelope me-2"></i>Correo Electrónico
                                </h5>
                                <p class="mb-2">Envíanos un email y te responderemos lo antes posible</p>
                                <a href="mailto:info@proyectoecuador.com" class="text-white text-decoration-underline fs-5 fw-semibold">
                                    info@proyectoecuador.com
                                </a>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <h5 class="fw-bold mb-3">
                                    <i class="bi bi-whatsapp me-2"></i>WhatsApp
                                </h5>
                                <p class="mb-2">Escríbenos directamente por WhatsApp</p>
                                <a href="https://wa.me/+593996980222?text=Hola, necesito información" 
                                   target="_blank" 
                                   class="text-white text-decoration-underline fs-5 fw-semibold">
                                    +593 996 980 222
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Ubicación -->
                    <div class="row justify-content-center mt-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <i class="bi bi-geo-alt fs-4 mb-2 d-block"></i>
                                <h6 class="fw-bold">Ubicación</h6>
                                <span class="text-light">Quito - Ecuador</span>
                            </div>
                        </div>
                    </div>


                </div>

            </div>
        </div>
    </div>
</div>

<!-- SVG decorativo después del contenido -->
<div class="position-relative" style="z-index: 1;">
    <?php include "views/modules/svgs/svgs.php"; ?>
</div>