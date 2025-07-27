<!--=================================
TOP MEJORADO RESPONSIVO
==================================-->
<div class="container-fluid p-0" id="top">
  <div class="container px-3">
    <div class="row align-items-center justify-content-between py-2">

      <!-- LOGO -->
      <div class="col-12 col-sm-auto d-flex justify-content-center justify-content-sm-start mb-2 mb-sm-0">
        <a href="/" class="d-inline-block" style="margin-left: 0;">
          <img src="/views/assets/img/ProyectoEcuadorLogo.png" alt="Proyecto Ecuador"
               style="height: 80px; max-width: 100%; object-fit: contain; filter: drop-shadow(0 0 3px rgba(0,0,0,0.7));">
        </a>
      </div>

      <!-- ACCIONES: BOTÓN + WHATSAPP -->
      <div class="col-12 col-sm-auto d-flex flex-column flex-sm-row align-items-center justify-content-center gap-2 gap-sm-3">

        <!-- Botón que redirige a la página de consulta -->
        <a href="/consultar"
           class="text-decoration-none px-3 py-2 fw-semibold rounded text-center"
           style="background-color: transparent; color: <?php echo urldecode($template->color0_template) ?>; border: 1px solid <?php echo urldecode($template->color0_template) ?>; transition: all 0.3s ease;">
          Ver mis números asignados
        </a>

        <!-- LINK WHATSAPP -->
        <a href="https://wa.me/<?php echo urldecode($raffle->phone_raffle) ?>?text=duda" target="_blank"
           class="text-white text-decoration-none fw-medium">
          <i class="bi bi-whatsapp"></i> Atención al cliente
        </a>

      </div>
    </div>
  </div>
</div>
