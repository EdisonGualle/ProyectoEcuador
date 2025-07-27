<!-- HERO Checkout personalizado para políticas de privacidad -->
<div class="container-fluid p-0" id="heroCheckout">

    <!-- Fondo degradado celeste -->
    <div class="container-fluid py-5">

        <!-- Tarjeta centrada y separada -->
        <div class="container d-flex justify-content-center align-items-center" style="padding-top: 0px; padding-bottom: 200px;">
            <div class="rounded-4 shadow-xl p-4 px-5 text-white responsive-card"
                style="max-width: 960px; background: <?php echo $template->color1_template; ?>; z-index: 2; position: relative; border: 1px solid rgba(255,255,255,0.2); backdrop-filter: blur(3px);">

                <!-- Título destacado centrado y con estilo de botón -->
                <h1 class="btn btn-default btn-lg rounded pt-3 text-uppercase b1 border-0 text-white d-block mx-auto text-center mb-4 responsive-title">
                    POLÍTICAS DE PRIVACIDAD PROYECTO ECUADOR
                </h1>

                <!-- Contenido legal -->
                <ul class="ps-0" style="list-style: none;">
                    <li class="mb-4">
                        <h5 class="mb-2">1. Recopilación de información</h5>
                        <p>PROYECTO ECUADOR recopila información personal cuando usted participa en nuestros sorteos, incluyendo nombre, dirección de correo electrónico, número de teléfono y datos de contacto.</p>
                    </li>
                    <li class="mb-4">
                        <h5 class="mb-2">2. Uso de la información</h5>
                        <p>La información recopilada se utiliza para gestionar su participación en sorteos, comunicar resultados y enviar información sobre futuros eventos. No compartimos sus datos con terceros sin su consentimiento explícito.</p>
                    </li>
                    <li class="mb-4">
                        <h5 class="mb-2">3. Protección de datos</h5>
                        <p>Implementamos medidas de seguridad técnicas y organizativas para proteger sus datos personales contra accesos no autorizados, alteración o destrucción.</p>
                    </li>
                    <li class="mb-4">
                        <h5 class="mb-2">4. Cookies y tecnologías similares</h5>
                        <p>Nuestro sitio web utiliza cookies para mejorar la experiencia del usuario. Puede gestionar sus preferencias de cookies a través de la configuración de su navegador.</p>
                    </li>
                    <li class="mb-4">
                        <h5 class="mb-2">5. Derechos de los usuarios</h5>
                        <p>Usted tiene derecho a acceder, rectificar, cancelar u oponerse al tratamiento de sus datos personales. Para ejercer estos derechos, contáctenos a través de nuestros canales oficiales.</p>
                    </li>
                    <li class="mb-4">
                        <h5 class="mb-2">6. Menores de edad</h5>
                        <p>Nuestros servicios están dirigidos a mayores de 18 años. No recopilamos conscientemente información de menores sin el consentimiento de sus padres o tutores.</p>
                    </li>
                    <li class="mb-4">
                        <h5 class="mb-2">7. Cambios en la política</h5>
                        <p>Nos reservamos el derecho de modificar esta política de privacidad. Cualquier cambio será publicado en esta sección con la fecha de actualización correspondiente.</p>
                    </li>
                    <li class="mb-4">
                        <h5 class="mb-2">8. Contacto</h5>
                        <p>Para cualquier consulta sobre protección de datos, puede contactarnos al correo: <strong>privacidad@proyectoecuador.com</strong> o a nuestro número de WhatsApp oficial.</p>
                    </li>
                    <li>
                        <h5 class="mb-2">9. Legislación aplicable</h5>
                        <p>Esta política se rige por la Ley Orgánica de Protección de Datos Personales de Ecuador y normativas complementarias.</p>
                    </li>
                </ul>

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

    /* Media queries para responsividad */
    @media (max-width: 768px) {
        .responsive-card {
            margin: 1rem !important;
            padding: 1.5rem 1rem !important;
            margin-top: 0.5rem !important;
            /* Reducido solo en móvil */
        }

        .responsive-title {
            font-size: 1.2rem !important;
            padding: 1rem !important;
            line-height: 1.3 !important;
            margin-bottom: 1rem !important;
            /* Reducido solo en móvil */
        }

        .container-fluid .container {
            padding-bottom: 100px !important;
        }

        #heroCheckout .container-fluid {
            padding-top: 1rem !important;
            /* Reducido solo en móvil */
            padding-bottom: 1rem !important;
        }
    }

    @media (max-width: 576px) {
        .responsive-card {
            margin: 0.5rem !important;
            padding: 1rem 0.75rem !important;
            margin-top: 0.25rem !important;
            /* Más reducido en móvil pequeño */
        }

        .responsive-title {
            font-size: 1rem !important;
            padding: 0.75rem !important;
            margin-bottom: 0.75rem !important;
            /* Más reducido en móvil pequeño */
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

        .mb-4 {
            margin-bottom: 1.5rem !important;
        }

        #heroCheckout .container-fluid {
            padding-top: 0.5rem !important;
            /* Más reducido en móvil pequeño */
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
            /* Mínimo margen en móviles muy pequeños */
        }

        h5 {
            font-size: 0.95rem !important;
            margin-bottom: 0.5rem !important;
        }

        p {
            font-size: 0.85rem !important;
            line-height: 1.3 !important;
        }
    }
</style>