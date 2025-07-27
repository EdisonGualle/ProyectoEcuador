<?php
$type = $raffle->type_imghero_raffle ?? 'estatico';
$galleries = [];
$productImage = null;

if (!empty($raffle->id_product_raffle)) {
  if ($type === 'dinamico') {
    $url = "galleries?linkTo=id_product_gallery&equalTo=" . $raffle->id_product_raffle;
    $response = CurlController::request($url, "GET", []);
    $galleries = ($response->status === 200) ? array_slice($response->results, 0, 4) : [];
  } else {
    $url = "products?linkTo=id_product&equalTo=" . $raffle->id_product_raffle;
    $response = CurlController::request($url, "GET", []);
    if ($response->status === 200 && !empty($response->results[0]->img_product)) {
      $productImage = urldecode($response->results[0]->img_product);
    }
  }
}
?>

<!-- Decoraciones visuales -->
<figure class="position-absolute" style="top:0;left:0">
  <img src="/views/assets/img/car-star.png" class="img-fluid" loading="eager">
</figure>

<figure class="position-absolute ray" style="top:0;left:0">
  <img src="/views/assets/img/car-ray.png" class="img-fluid" loading="eager">
</figure>

<figure class="position-absolute colorImage" style="top:0;left:0">
  <img src="/views/assets/img/car-light.png" class="img-fluid" loading="eager">
</figure>

<!-- Contenedor principal -->
<div class="position-relative hero-container">
  <div class="image-collage">
    <?php if ($type === 'dinamico' && !empty($galleries)): ?>
      <?php
      $uniqueGalleries = array_unique($galleries, SORT_REGULAR);
      $uniqueCount = count($uniqueGalleries);
      ?>
      <?php if ($uniqueCount === 1): ?>
        <div class="collage-hero single-img">
          <img src="<?= urldecode($uniqueGalleries[0]->img_gallery) ?>" alt="Premio" class="collage-img" loading="lazy">
        </div>
      <?php else: ?>
        <div class="collage-hero multiple-images">
          <?php foreach (array_slice($uniqueGalleries, 0, 4) as $index => $img): ?>
            <img
              src="<?= urldecode($img->img_gallery) ?>"
              alt="Premio <?= $index + 1 ?>"
              class="collage-img img-<?= $index + 1 ?>"
              loading="lazy">
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

    <?php elseif ($type === 'estatico' && !empty($productImage)): ?>
      <div class="collage-hero static-img">
        <img
          src="<?= $productImage ?>"
          alt="Producto"
          class="static-product-img"
          loading="eager"
          decoding="async">
      </div>
    <?php endif; ?>
  </div>
</div>

<style>
  .hero-container {
    min-height: 400px;
    display: flex;
    justify-content: center;
    align-items: center;
  }

  .image-collage {
    position: relative;
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 2;
  }

  /* Estilos base para ambos modos */
  .collage-hero {
    position: relative;
    margin: 0 auto;
  }

  /* Modo dinámico - múltiples imágenes */
  .collage-hero.multiple-images {
    width: clamp(320px, 80vw, 500px);
    height: clamp(300px, 75vw, 400px);
  }

  .collage-img {
    border: 4px solid white;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.25);
    border-radius: 12px;
    position: absolute;
    object-fit: cover;
    transition: all 0.3s ease;
    height: clamp(90px, 22vw, 150px);
  }

  /* Posicionamiento específico para PC */
  .collage-hero.multiple-images .img-1 {
    top: 10px;
    left: 0;
    transform: rotate(-10deg);
    z-index: 4;
    width: clamp(130px, 28vw, 200px);
  }

  .collage-hero.multiple-images .img-2 {
    top: 10px;
    right: 0;
    transform: rotate(8deg);
    z-index: 3;
    width: clamp(130px, 28vw, 200px);
  }

  .collage-hero.multiple-images .img-3 {
    top: 180px;
    left: 0;
    transform: rotate(6deg);
    z-index: 2;
    width: clamp(130px, 28vw, 200px);
  }

  .collage-hero.multiple-images .img-4 {
    top: 180px;
    right: 0;
    transform: rotate(-6deg);
    z-index: 1;
    width: clamp(130px, 28vw, 200px);
  }

  /* Efecto hover */
  .collage-img:hover {
    transform: scale(1.05) rotate(0deg) !important;
    z-index: 10;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.35);
  }

  /* Modo estático */
  .collage-hero.static-img {
    width: clamp(280px, 60vw, 450px);
    height: clamp(280px, 60vw, 450px);
  }

  .static-product-img {
    width: 90%;
    height: 90%;
    border-radius: 20px;
    border: 5px solid white;
    box-shadow: 0 15px 25px rgba(0, 0, 0, 0.4);
    object-fit: cover;
  }

  /* Responsive para móviles */
  @media (max-width: 768px) {
    .hero-container {
      min-height: 300px;
    }
    
    .collage-hero.multiple-images {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 15px;
      position: relative;
      height: auto;
      width: 90vw;
    }

    .collage-hero.multiple-images .collage-img {
      position: relative;
      transform: rotate(0deg) !important;
      width: 100% !important;
      height: auto !important;
      max-height: 40vw;
      top: auto !important;
      left: auto !important;
      right: auto !important;
    }
  }

  /* Ajustes para tablets */
  @media (min-width: 769px) and (max-width: 1024px) {
    .collage-hero.multiple-images {
      width: 400px;
      height: 350px;
    }
    
    .collage-hero.multiple-images .collage-img {
      width: 180px;
      height: 130px;
    }
  }

  /* Ajustes específicos para PC */
  @media (min-width: 1025px) {
    .collage-hero.multiple-images {
      width: 420px;
      height: 400px;
    }
    
    .collage-hero.multiple-images .collage-img {
      width: 200px;
      height: 150px;
    }
  }
</style>