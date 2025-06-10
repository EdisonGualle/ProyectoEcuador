<?php
// Obtener las imágenes del producto del sorteo actual
$galleries = [];
if (isset($raffle->id_product)) {
    $url = "galleries?linkTo=id_product_gallery&equalTo=" . $raffle->id_product;
    $galleriesResponse = CurlController::request($url, "GET", []);
    $galleries = ($galleriesResponse->status == 200) ? array_slice($galleriesResponse->results, 0, 4) : [];
}
?>

<!-- Elementos visuales del collage -->
<figure class="position-absolute" style="top:0;left:0">
  <img src="/views/assets/img/car-star.png" class="img-fluid">
</figure>

<figure class="position-absolute ray" style="top:0;left:0">
  <img src="/views/assets/img/car-ray.png" class="img-fluid">
</figure>

<figure class="position-absolute colorImage" style="top:0;left:0">
  <img src="/views/assets/img/car-light.png" class="img-fluid">
</figure>

<div class="position-relative" style="min-height: 400px;">

  <!-- Estrella giratoria -->
  <img src="/views/assets/img/star-bg.png"
       alt="Decoración estrella"
       class="position-absolute top-50 start-50 translate-middle"
       style="width: 500px; animation: spin 40s linear infinite; opacity: 0.25; z-index: 0; pointer-events: none;">

  <!-- Collage -->
  <div class="position-absolute start-50 translate-middle image-collage" style="top: 58%; z-index: 2;">

    <style>
      .collage-hero {
        width: 420px;
        height: 430px;
        position: relative;
        transform: scale(1.25);
        transform-origin: center;
        transition: transform 0.3s ease;
      }

      .image-collage:hover .collage-hero {
        transform: scale(1.35);
      }

      .collage-img {
        border: 4px solid white;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.25);
        border-radius: 12px;
        transition: transform 0.3s ease;
        position: absolute;
        width: 190px;
        height: 150px;
        object-fit: cover;
      }

      .collage-img:hover {
        transform: scale(1.08) rotate(0deg) !important;
        z-index: 10 !important;
      }

      @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
      }
    </style>

    <?php if (!empty($galleries)): ?>
      <div class="collage-hero">
        <?php foreach ($galleries as $index => $img): ?>
          <?php
            $style = match ($index) {
              0 => 'top: 20px; left: 0px; transform: rotate(-10deg); z-index: 4;',
              1 => 'top: 20px; right: 18px; transform: rotate(8deg); z-index: 3;',
              2 => 'top: 170px; left: 0px; transform: rotate(6deg); z-index: 2;',
              3 => 'top: 170px; right: 18px; transform: rotate(-6deg); z-index: 1;',
              default => '',
            };
          ?>
          <img src="<?= urldecode($img->img_gallery) ?>" alt="Premio <?= $index + 1 ?>" class="collage-img" style="<?= $style ?>">
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

  </div>
</div>
