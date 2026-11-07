<?php
require_once __DIR__ . '/../../backend/includes/layout.php';
require_once __DIR__ . '/../../backend/classes/CategoryClass.php';
require_once __DIR__ . '/../../backend/classes/ProductClass.php';

$categoryobj = new Category();
$productobj  = new Product();
$categories  = $categoryobj->getallcategories();
$featured    = $productobj->getproducts('', 0, 8, 0);   // latest 8 products

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
      Home | Glow &amp; Co.
    </title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&amp;family=Jost:wght@300;400;500&amp;display=swap" rel="stylesheet">
    <!-- Shared styles (tokens, layout, components) -->
    <link rel="stylesheet" href="css/base.css">
    <!-- Page-specific styles -->
    <link rel="stylesheet" href="css/index.css">
  </head>
  <body class="store">
    <?php render_header('home'); ?>
    <main>

      <section class="hero" style="background-image:linear-gradient(90deg,rgba(246,237,224,.92) 0%,rgba(246,237,224,.62) 55%,rgba(246,237,224,.25) 100%),url('https://alwaysandforevercandles.com/cdn/shop/articles/DALL_E_2025-01-05_14.35.39_-_A_cozy_and_elegant_setting_featuring_several_soy_wax_candles_burning_gently._The_candles_are_arranged_on_a_wooden_table_surrounded_by_soft_light_and_n.webp?v=1736084151');background-size:cover;background-position:center">
        <div class="container hero-grid">
          <div class="hero-text">
            <span class="eyebrow">
              Handmade in small batches
            </span>
            <h1>
              Light a little calm into every room
            </h1>
            <p>
              Soy wax candles, reed diffusers and soft-toned decor, crafted with warm, natural scents for slow evenings at home.
            </p>
            <div class="hero-btns">
              <a href="products.php" class="btn">
                Shop the collection
              </a>
              <a href="#categories" class="btn btn-outline">
                Explore categories
              </a>
            </div>
          </div>
        </div>
      </section>
      <section class="section" id="categories">
        <div class="container">
          <div class="sec-head">
            <span class="eyebrow">
              Browse
            </span>
            <h2>
              Shop by category
            </h2>
          </div>
          <div class="grid grid-4">
            <?php foreach ($categories as $cat): ?>
            <a href="products.php?category=<?= (int) $cat['id'] ?>" class="cat-card">
              <span class="thumb c<?= (($cat['id'] - 1) % 4) * 2 + 1 ?>"<?= image_style($cat['image']) ?>>
              </span>
              <h3>
                <?= e($cat['name']) ?>
              </h3>
              <p>
                <?= e($cat['description']) ?>
              </p>
            </a>
            <?php endforeach; ?>
          </div>
        </div>
      </section>
      <section class="section alt">
        <div class="container">
          <div class="sec-head">
            <span class="eyebrow">
              Favourites
            </span>
            <h2>
              Featured products
            </h2>
          </div>
          <div class="grid grid-4">
            <?php foreach ($featured as $product): render_product_card($product); endforeach; ?>
          </div>
        </div>
      </section>
      <section class="section">
        <div class="container grid grid-3 perks">
          <div>
            <h3>
              Hand-poured
            </h3>
            <p>
              Every candle is poured by hand using clean-burning soy wax.
            </p>
          </div>
          <div>
            <h3>
              Cash on delivery
            </h3>
            <p>
              Order now and pay only when your parcel arrives at your door.
            </p>
          </div>
          <div>
            <h3>
              Giftable
            </h3>
            <p>
              Curated gift sets wrapped and ready to share.
            </p>
          </div>
        </div>
      </section>
    </main>
    <?php render_footer(); ?>
  </body>
</html>