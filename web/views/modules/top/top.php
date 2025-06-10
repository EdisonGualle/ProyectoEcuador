<!--=================================
TOP MEJORADO SIN FONDO RECTANGULAR Y CENTRADO VISUALMENTE
==================================-->

<div class="container-fluid p-0" id="top">
    <div class="container p-0">
        <div class="row align-items-center justify-content-between py-2 px-3">

            <!-- LOGO -->
            <div class="col-auto d-flex align-items-center">
                <a href="/" class="d-inline-block" style="margin-left: 55px;">
                    <img src="/views/assets/img/ProyectoEcuadorLogo.png" alt="Proyecto Ecuador"
                        style="height: 100px; max-width: 100%; object-fit: contain; filter: drop-shadow(0 0 3px rgba(0,0,0,0.7));">
                </a>
            </div>

            <!-- ACCIONES: BOTÓN + WHATSAPP -->
            <div class="col-auto d-flex align-items-center gap-3">

                <!-- Botón que redirige a la página de consulta -->
                <a href="/consultar"
                    onmouseover="this.style.backgroundColor='<?php echo urldecode($template->color4_template) ?>'; this.style.color='white';"
                    onmouseout="this.style.backgroundColor='transparent'; this.style.color='<?php echo urldecode($template->color0_template) ?>';"
                    style="
                    padding: 8px 18px;
                    font-weight: 600;
                    color: <?php echo urldecode($template->color0_template) ?>;
                    background-color: transparent;
                    text-decoration: none;
                    transition: all 0.3s ease;
                ">
                    Ver mis números asignados
                </a>

                <!-- LINK WHATSAPP -->
                <a href="https://wa.me/<?php echo urldecode($raffle->phone_raffle) ?>?text=duda" target="_blank"
                    style="color: white; text-decoration: none; font-weight: 500; font-size: 1rem;">
                    <i class="bi bi-whatsapp"></i> Atención al cliente
                </a>

            </div>
        </div>
        
    </div>
</div>