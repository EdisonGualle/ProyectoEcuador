<div class="container-fluid p-0 position-relative" id="hero">

  <!-- Imagen de fondo -->
  <figure class="position-absolute" style="top:0;left:0;">
    <img src="/views/assets/img/hero-building.png" class="img-fluid">
  </figure>

  <!-- Formulario centrado y elevado -->
  <div class="container d-flex justify-content-center py-5 position-relative" style="z-index: 1;">
    <div class="col-12 col-md-10 col-lg-9">
      <div class="card bg p-5 rounded-4 text-center shadow-lg"
        style="min-height: 320px; background: <?php echo urldecode($template->color3_template) ?> !important; color: <?php echo urldecode($template->color0_template) ?> !important;">

        <h2 class="mb-4 text-white text-uppercase josefin-sans-700">Consulta tus números asignados</h2>

        <!-- Formulario -->
        <form method="GET" class="mb-4">
          <div class="row g-3 justify-content-center">
            <div class="col-md-5">
              <input type="email" name="email" class="form-control form-control-lg" placeholder="Tu correo electrónico" required>
            </div>
            <div class="col-md-4">
              <select name="raffle_id" class="form-select form-select-lg">
                <option value="">Todos los sorteos</option>
                <?php
                $raffles = CurlController::request("raffles?select=id_raffle,tittle", "GET", []);
                if ($raffles->status == 200) {
                  foreach ($raffles->results as $r) {
                    $selected = (isset($_GET['raffle_id']) && $_GET['raffle_id'] == $r->id_raffle) ? 'selected' : '';
                    echo "<option value='{$r->id_raffle}' $selected>{$r->tittle}</option>";
                  }
                }
                ?>
              </select>
            </div>
            <div class="col-md-3">
              <button type="submit" class="btn b1 btn-lg w-100 rounded-pill">Consultar</button>
            </div>
          </div>
        </form>

        <!-- Resultados -->
        <?php
        if (isset($_GET['email'])) {
          $email = $_GET['email'];
          $selectedRaffle = $_GET['raffle_id'] ?? null;
          $method = "GET";
          $fields = [];

          $urlClients = "clients?linkTo=email_client&equalTo=" . urlencode($email) . "&select=id_client";
          $clientsRes = CurlController::request($urlClients, $method, $fields);

          if ($clientsRes->status == 200 && count($clientsRes->results) > 0) {
            $idClients = array_map(fn($c) => $c->id_client, $clientsRes->results);
            $numbersByRaffle = [];

            foreach ($idClients as $id_client) {
              $urlSales = "sales?linkTo=id_client_sale,status_sale&equalTo=$id_client,PAID";
              $salesRes = CurlController::request($urlSales, $method, $fields);

              if ($salesRes->status == 200 && count($salesRes->results) > 0) {
                foreach ($salesRes->results as $sale) {
                  $raffleId = $sale->id_raffle_sale;
                  $number = $sale->number_sale;
                  if (!$selectedRaffle || $raffleId == $selectedRaffle) {
                    $numbersByRaffle[$raffleId][] = $number;
                  }
                }
              }
            }

            if (count($numbersByRaffle) > 0) {
              echo "<div class='text-center mb-4 text-white'><strong>Números asignados por sorteo:</strong></div>";
              foreach ($numbersByRaffle as $raffleId => $numbers) {
                $urlRaffle = "raffles?linkTo=id_raffle&equalTo=$raffleId&select=tittle";
                $raffleRes = CurlController::request($urlRaffle, $method, $fields);
                $raffleName = ($raffleRes->status == 200 && count($raffleRes->results) > 0) ? $raffleRes->results[0]->tittle : "Sorteo #$raffleId";

                echo "<div class='mb-4 text-center'>";
                echo "<h5 class='text-light mb-2'>🔹 $raffleName</h5>";
                echo "<div class='d-flex flex-wrap justify-content-center gap-2'>";
                foreach ($numbers as $num) {
                  echo "<span class='badge bg-success fs-5 p-2 px-3'>$num</span>";
                }
                echo "</div></div>";
              }
            } else {
              echo "<div class='alert alert-warning text-center'>No se encontraron números asignados con estado <strong>PAID</strong>.</div>";
            }
          } else {
            echo "<div class='alert alert-danger text-center mt-4'>Este correo no está registrado en el sistema.</div>";
          }
        }
        ?>

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