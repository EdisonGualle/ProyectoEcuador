<!-- HERO Checkout personalizado para contacto -->
<div class="container-fluid p-0" id="heroCheckout">

    <!-- Fondo degradado celeste -->
    <div class="container-fluid py-5">

        <!-- Tarjeta centrada y separada -->
        <div class="container d-flex justify-content-center align-items-center" style="padding-top: 0px; padding-bottom: 200px;">
            <div class="rounded-4 shadow-xl p-4 px-5 text-white responsive-card"
                style="max-width: 960px; background: <?php echo $template->color1_template; ?>; z-index: 2; position: relative; border: 1px solid rgba(255,255,255,0.2); backdrop-filter: blur(3px);">

                <!-- Título destacado centrado y con estilo de botón -->
                <h1 class="btn btn-default btn-lg rounded pt-3 text-uppercase b1 border-0 text-white d-block mx-auto text-center mb-4 responsive-title">
                    CONTÁCTENOS - PROYECTO ECUADOR
                </h1>

                <!-- Contenido de contacto -->
                <div class="row g-4 mb-4">
                    <!-- Columna 1: Correo electrónico -->
                    <div class="col-md-6">
                        <div class="mb-3 text-center">
                            <h5 class="mb-2 fw-bold">
                                <i class="bi bi-envelope me-2"></i>CORREO ELECTRÓNICO
                            </h5>
                            <p class="mb-3">Para consultas generales o información sobre sorteos</p>
                            <a href="mailto:contacto@proyectoecuador.com" 
                               class="text-white text-decoration-underline d-inline-block responsive-link">
                               contacto@proyectoecuador.com
                            </a>
                        </div>
                    </div>

                    <!-- Columna 2: WhatsApp -->
                    <div class="col-md-6">
                        <div class="mb-3 text-center">
                            <h5 class="mb-2 fw-bold">
                                <i class="bi bi-whatsapp me-2"></i>WHATSAPP
                            </h5>
                            <p class="mb-3">Atención directa y soporte técnico</p>
                            <a href="https://wa.me/+593996980222?text=Hola,%20necesito%20información" 
                               target="_blank"
                               class="text-white text-decoration-underline d-inline-block responsive-link">
                               +593 99 698 0222
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Ubicación -->
                <div class="row justify-content-center mt-4">
                    <div class="col-md-8 text-center">
                        <div class="mb-3">
                            <i class="bi bi-geo-alt-fill fs-4 mb-2 d-block"></i>
                            <h5 class="fw-bold mb-2">UBICACIÓN</h5>
                            <p class="mb-0">Quito, Ecuador</p>
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

<style>
    /* Estilos base (para PC) */
    #heroCheckout .responsive-card {
        margin-top: 2rem;
    }

    #heroCheckout .responsive-title {
        margin-bottom: 2rem;
    }

    .responsive-link {
        font-size: 1.1rem;
        transition: all 0.3s ease;
    }

    .responsive-link:hover {
        opacity: 0.8;
    }

    /* Media queries para responsividad */
    @media (max-width: 768px) {
        .responsive-card {
            margin: 1rem !important;
            padding: 1.5rem 1rem !important;
            margin-top: 0.5rem !important;
        }

        .responsive-title {
            font-size: 1.2rem !important;
            padding: 1rem !important;
            line-height: 1.3 !important;
            margin-bottom: 1rem !important;
        }

        .container-fluid .container {
            padding-bottom: 100px !important;
        }

        .responsive-link {
            font-size: 1rem !important;
        }

        #heroCheckout .container-fluid {
            padding-top: 1rem !important;
            padding-bottom: 1rem !important;
        }
    }

    @media (max-width: 576px) {
        .responsive-card {
            margin: 0.5rem !important;
            padding: 1rem 0.75rem !important;
            margin-top: 0.25rem !important;
        }

        .responsive-title {
            font-size: 1rem !important;
            padding: 0.75rem !important;
            margin-bottom: 0.75rem !important;
        }

        .container-fluid .container {
            padding-bottom: 50px !important;
        }

        h5 {
            font-size: 1rem !important;
        }

        p {
            font-size: 0.9rem !important;
            line-height: 1.4 !important;
        }

        .responsive-link {
            font-size: 0.9rem !important;
        }

        .bi {
            font-size: 1.1rem !important;
        }

        #heroCheckout .container-fluid {
            padding-top: 0.5rem !important;
            padding-bottom: 0.5rem !important;
        }
    }

    @media (max-width: 400px) {
        .responsive-card {
            margin: 0.25rem !important;
            padding: 0.75rem 0.5rem !important;
        }

        .responsive-title {
            font-size: 0.9rem !important;
            padding: 0.5rem !important;
            margin-bottom: 0.5rem !important;
        }

        h5 {
            font-size: 0.95rem !important;
            margin-bottom: 0.5rem !important;
        }

        p {
            font-size: 0.85rem !important;
            line-height: 1.3 !important;
        }

        .responsive-link {
            font-size: 0.85rem !important;
        }

        .bi {
            font-size: 1rem !important;
        }
    }
</style>