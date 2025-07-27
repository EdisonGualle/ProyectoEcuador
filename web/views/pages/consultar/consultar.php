<div class="container-fluid p-0 position-relative" id="hero">

  <!-- Estilos responsivos embebidos -->
  <style>
    @media (max-width: 767px) {
      #hero h1 {
        font-size: 1.3rem !important;
        padding: 0.75rem 1rem !important;
      }

      #hero input[type="email"] {
        font-size: 1rem;
        padding: 0.75rem 1rem;
      }

      #hero .row.g-3 > div {
        flex: 0 0 100%;
        max-width: 100%;
      }

      #hero button[type="submit"] {
        margin-top: 0.5rem;
        font-size: 1rem;
        padding: 0.75rem 1.25rem;
      }

      #hero .p-5 {
        padding: 1rem !important;
        min-height: 0 !important;
        height: auto !important;
      }

      #hero .container.d-flex {
        padding-top: 0 !important;
        padding-bottom: 0 !important;
        margin-bottom: 0 !important;
      }

      #hero .col-12 {
        padding: 0 !important;
      }

      #hero form {
        margin-bottom: 0 !important;
      }
    }
  </style>

  <!-- Imagen de fondo -->
  <figure class="position-absolute w-100" style="top:0;left:0;">
    <img src="/views/assets/img/hero-building.png" class="img-fluid w-100" alt="Hero Background">
  </figure>

  <!-- Formulario centrado y elevado -->
  <div class="container d-flex justify-content-center py-5 position-relative" style="z-index: 1;">
    <div class="col-12 col-md-10 col-lg-9">
      <div class="p-5 text-center"
        style="min-height: 320px; background: transparent !important; color: <?= urldecode($template->color0_template) ?> !important;">
        
        <h1 class="">
          Consulta tus números de la suerte
        </h1>

        <!-- Formulario -->
        <form method="GET" action="consultados" class="mb-4">
          <div class="row g-3 justify-content-center">
            <div class="col-12 col-md-6">
              <input type="email" name="email"
                class="form-control form-control-lg text-center"
                placeholder="Tu correo electrónico" required>
            </div>
            <div class="col-12 col-md-3">
              <button type="submit" class="btn b1 btn-lg w-100 rounded-pill">Consultar</button>
            </div>
          </div>
        </form>

        <!-- Mensaje si ya se envió -->
        <?php if (isset($_GET['email'])): ?>
          <div class="alert alert-success text-center">
            <strong>¡Consulta realizada con éxito!</strong><br>
            Los resultados han sido enviados para el correo: <?= htmlspecialchars($_GET['email']) ?>
          </div>
        <?php endif; ?>

      </div>
    </div>
  </div>

  <!-- SVG decorativo -->
  <div>
    <?php include "views/modules/svgs/svgs.php"; ?>
  </div>
</div>

<!-- Validación de correo en JS -->
<script>
  document.querySelector('form')?.addEventListener('submit', function(e) {
    const email = document.querySelector('input[name="email"]').value.trim();
    if (!email.includes('@')) {
      alert("Ingresa un correo electrónico válido.");
      e.preventDefault();
    }
  });
</script>
