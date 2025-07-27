<div class="container-fluid p-0" id="heroCheckout">
  <style>
    /* Estilos base para números */
    #heroCheckout .numbers {

      font-weight: bold !important;
      display: inline-flex;
      align-items: center;
      justify-content: center;
    }

    #heroCheckout .numbers span {
      padding: 0 !important;
    }

    /* Centrar contenido */
    #heroCheckout .position-relative img {
      display: block;
      margin: 0 auto;
      max-width: 100%;
      height: auto;
    }

    @media (max-width: 767px) {
      #heroCheckout h1 {
        font-size: 1.2rem !important;
        padding: 0.6rem 0.8rem !important;
        line-height: 1.3;
        margin-bottom: 0.8rem !important;
      }

      #heroCheckout h2 {
        font-size: 0.95rem !important;
        padding: 0.4rem 0.8rem !important;
        line-height: 1.3;
        margin-bottom: 0.8rem !important;
      }

      #heroCheckout h4 {
        font-size: 0.9rem !important;
        padding: 0.4rem 0.8rem !important;
        line-height: 1.3;
      }

      #heroCheckout .offset-3 {
        margin-left: 0 !important;
      }

      #heroCheckout .col-6 {
        width: 100% !important;
        padding: 0 15px;
      }

      #heroCheckout .numbers {
        font-size: 0.9rem !important;
        width: 45px !important;
        height: 45px !important;
        line-height: 45px !important;
        margin: 0.25rem !important;
      }

      #heroCheckout .d-flex.flex-wrap {
        padding: 0.8rem 0.4rem !important;
        margin-bottom: 1rem;
      }

      #heroCheckout .col.p-1.mb-5.py-lg-5.px-lg-5.position-relative {
        padding: 0.8rem !important;
        margin-bottom: 1.5rem !important;
        text-align: center;
      }

      #heroCheckout .container>.row {
        padding-bottom: 0.5rem !important;
        padding-top: 1rem !important;
      }

      #heroCheckout>.position-relative {
        margin-top: -30px;
      }

      #heroCheckout .container-fluid .container {
        padding: 0 15px;
      }

      #heroCheckout .text-center.position-relative {
        margin-bottom: 1rem;
      }
    }

    @media (max-width: 480px) {
      #heroCheckout h1 {
        font-size: 1.1rem !important;
        padding: 0.5rem 0.6rem !important;
        margin-bottom: 0.6rem !important;
      }

      #heroCheckout h2 {
        font-size: 0.85rem !important;
        padding: 0.3rem 0.6rem !important;
        margin-bottom: 0.6rem !important;
      }

      #heroCheckout h4 {
        font-size: 0.8rem !important;
        padding: 0.3rem 0.6rem !important;
      }

      #heroCheckout .numbers {
        font-size: 0.8rem !important;
        width: 40px !important;
        height: 40px !important;
        line-height: 40px !important;
        margin: 0.2rem !important;
      }

      #heroCheckout .d-flex.flex-wrap {
        padding: 0.6rem 0.2rem !important;
      }

      #heroCheckout .col.p-1.mb-5.py-lg-5.px-lg-5.position-relative {
        padding: 0.6rem !important;
        margin-bottom: 1rem !important;
      }

      #heroCheckout .container-fluid .container {
        padding: 0 10px;
      }
    }

    @media (max-width: 360px) {
      #heroCheckout h1 {
        font-size: 1rem !important;
        padding: 0.4rem 0.5rem !important;
      }

      #heroCheckout h2 {
        font-size: 0.8rem !important;
        padding: 0.25rem 0.5rem !important;
      }

      #heroCheckout h4 {
        font-size: 0.75rem !important;
        padding: 0.25rem 0.5rem !important;
      }

      #heroCheckout .numbers {
        font-size: 0.7rem !important;
        width: 36px !important;
        height: 36px !important;
        line-height: 36px !important;
        margin: 0.15rem !important;
      }

      #heroCheckout .d-flex.flex-wrap {
        padding: 0.4rem 0.1rem !important;
      }

      #heroCheckout .col.p-1.mb-5.py-lg-5.px-lg-5.position-relative {
        padding: 0.4rem !important;
        margin-bottom: 0.8rem !important;
      }
    }
  </style>

  <div class="container-fluid">
    <div class="container">
      <div class="row row-cols-1 pt-4">
        <div class="offset-3 col-6 textAlign text-dark text-center">
          <h1 class="josefin-sans-700 text-uppercase py-2 px-1 b1 pt-3 mb-0 mb-lg-3 rounded">
            Números asignados
          </h1>

          <?php if ($client): ?>
            <h2 class="p-2">
              Hola <?= TemplateController::capitalize($client->name_client) ?>, estos son tus números de la suerte:
            </h2>
          <?php else: ?>
            <h4 class="text-danger">No se encontró ningún número asignado para este correo.</h4>
          <?php endif; ?>

          <div class="text-center position-relative" style="z-index:1">
            <div class="d-flex flex-wrap w-100 justify-content-center p-2 rounded">
              <?php foreach ($numbers as $value): ?>
                <div class="h3 text-center numbers rounded-circle m-1" item="0" number="<?= $value ?>">
                  <span class="p-2"><?= $value ?></span>
                </div>
              <?php endforeach ?>
            </div>
          </div>

          <div class="col p-1 mb-5 py-lg-5 px-lg-5 position-relative">
            <?php include "views/modules/product/product.php"; ?>
          </div>

        </div>
      </div>
    </div>
  </div>

  <div class="position-relative">
    <?php include "views/modules/svgs/svgs.php"; ?>
  </div>
</div>