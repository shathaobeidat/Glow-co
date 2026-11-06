<?php require_once __DIR__ . '/../../backend/includes/layout.php'; ?>


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
    <?php render_header('index'); ?>
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
            <a href="products.html" class="cat-card">
              <span class="thumb c1" style="background-image:url('https://stoaparis.com/cdn/shop/files/1_3335af10-bb6e-4c55-ae71-e954b96ba81d.jpg?v=1758781146');background-size:cover;background-position:center">
              </span>
              <h3>
                Scented Candles
              </h3>
              <p>
                Hand-poured soy wax candles.
              </p>
            </a>
            <a href="products.html" class="cat-card">
              <span class="thumb c3" style="background-image:url('https://ayvae.com/cdn/shop/files/difusserwitvoiledor.png?v=1779637461&width=1445');background-size:cover;background-position:center">
              </span>
              <h3>
                Diffusers & Essential Oils
              </h3>
              <p>
                Reed diffusers and pure oils.
              </p>
            </a>
            <a href="products.html" class="cat-card">
              <span class="thumb c5" style="background-image:url('https://nalaliving.nl/cdn/shop/files/Marmer-Dienblad-Organisch-Beige-5.jpg?v=1770487787&width=1946');background-size:cover;background-position:center">
              </span>
              <h3>
                Trays & Coasters
              </h3>
              <p>
                Cement and marble decor.
              </p>
            </a>
            <a href="products.html" class="cat-card">
              <span class="thumb c7" style="background-image:url('https://static.wixstatic.com/media/3d95a4_80964430826842f6a26cb63b9a771e17~mv2.png/v1/fill/w_476%2Ch_476%2Cal_c%2Cq_85%2Cusm_0.66_1.00_0.01%2Cenc_avif%2Cquality_auto/3d95a4_80964430826842f6a26cb63b9a771e17~mv2.png');background-size:cover;background-position:center">
              </span>
              <h3>
                Gift Sets
              </h3>
              <p>
                Curated boxes, beautifully wrapped.
              </p>
            </a>
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
            <article class="card">
              <a href="product-detail.html" class="thumb c1" aria-label="Vanilla & Lavender Candle" style="background-image:url('https://stoaparis.com/cdn/shop/files/1_3335af10-bb6e-4c55-ae71-e954b96ba81d.jpg?v=1758781146');background-size:cover;background-position:center">
              </a>
              <div class="card-body">
                <span class="tag">
                  Scented Candles
                </span>
                <h3>
                  <a href="product-detail.html">
                    Vanilla & Lavender Candle
                  </a>
                </h3>
                <div class="card-foot">
                  <span class="price">
                    $24.00
                  </span>
                  <a href="cart.html" class="btn btn-sm">
                    Add to cart
                  </a>
                </div>
              </div>
            </article>
            <article class="card">
              <a href="product-detail.html" class="thumb c2" aria-label="Cinnamon & Wood Candle" style="background-image:url('https://esenciasyfragancias.com/cdn/shop/collections/velas.png?v=1758068590&width=750');background-size:cover;background-position:center">
              </a>
              <div class="card-body">
                <span class="tag">
                  Scented Candles
                </span>
                <h3>
                  <a href="product-detail.html">
                    Cinnamon & Wood Candle
                  </a>
                </h3>
                <div class="card-foot">
                  <span class="price">
                    $26.00
                  </span>
                  <a href="cart.html" class="btn btn-sm">
                    Add to cart
                  </a>
                </div>
              </div>
            </article>
            <article class="card">
              <a href="product-detail.html" class="thumb c3" aria-label="White Musk Reed Diffuser" style="background-image:url('https://ayvae.com/cdn/shop/files/difusserwitvoiledor.png?v=1779637461&width=1445');background-size:cover;background-position:center">
              </a>
              <div class="card-body">
                <span class="tag">
                  Diffusers & Essential Oils
                </span>
                <h3>
                  <a href="product-detail.html">
                    White Musk Reed Diffuser
                  </a>
                </h3>
                <div class="card-foot">
                  <span class="price">
                    $32.00
                  </span>
                  <a href="cart.html" class="btn btn-sm">
                    Add to cart
                  </a>
                </div>
              </div>
            </article>
            <article class="card">
              <a href="product-detail.html" class="thumb c4" aria-label="Floral Essential Oil" style="background-image:url('https://pestik.s15.cdn-upgates.com/_cache/f/d/fd46d3530b95abca2cf7251f62eecb45-neroli-esencialni-olej.png');background-size:cover;background-position:center">
              </a>
              <div class="card-body">
                <span class="tag">
                  Diffusers & Essential Oils
                </span>
                <h3>
                  <a href="product-detail.html">
                    Floral Essential Oil
                  </a>
                </h3>
                <div class="card-foot">
                  <span class="price">
                    $18.00
                  </span>
                  <a href="cart.html" class="btn btn-sm">
                    Add to cart
                  </a>
                </div>
              </div>
            </article>
            <article class="card">
              <a href="product-detail.html" class="thumb c5" aria-label="Beige Cement Tray" style="background-image:url('https://nalaliving.nl/cdn/shop/files/Marmer-Dienblad-Organisch-Beige-5.jpg?v=1770487787&width=1946');background-size:cover;background-position:center">
              </a>
              <div class="card-body">
                <span class="tag">
                  Trays & Coasters
                </span>
                <h3>
                  <a href="product-detail.html">
                    Beige Cement Tray
                  </a>
                </h3>
                <div class="card-foot">
                  <span class="price">
                    $29.00
                  </span>
                  <a href="cart.html" class="btn btn-sm">
                    Add to cart
                  </a>
                </div>
              </div>
            </article>
            <article class="card">
              <a href="product-detail.html" class="thumb c6" aria-label="Marble Coasters Set" style="background-image:url('https://i.etsystatic.com/42807522/r/il/4bfdec/8206338445/il_570xN.8206338445_bdfg.jpg');background-size:cover;background-position:center">
              </a>
              <div class="card-body">
                <span class="tag">
                  Trays & Coasters
                </span>
                <h3>
                  <a href="product-detail.html">
                    Marble Coasters Set
                  </a>
                </h3>
                <div class="card-foot">
                  <span class="price">
                    $35.00
                  </span>
                  <a href="cart.html" class="btn btn-sm">
                    Add to cart
                  </a>
                </div>
              </div>
            </article>
            <article class="card">
              <a href="product-detail.html" class="thumb c7" aria-label="Home Relaxation Box" style="background-image:url('https://static.wixstatic.com/media/3d95a4_80964430826842f6a26cb63b9a771e17~mv2.png/v1/fill/w_476%2Ch_476%2Cal_c%2Cq_85%2Cusm_0.66_1.00_0.01%2Cenc_avif%2Cquality_auto/3d95a4_80964430826842f6a26cb63b9a771e17~mv2.png');background-size:cover;background-position:center">
              </a>
              <div class="card-body">
                <span class="tag">
                  Gift Sets
                </span>
                <h3>
                  <a href="product-detail.html">
                    Home Relaxation Box
                  </a>
                </h3>
                <div class="card-foot">
                  <span class="price">
                    $58.00
                  </span>
                  <a href="cart.html" class="btn btn-sm">
                    Add to cart
                  </a>
                </div>
              </div>
            </article>
            <article class="card">
              <a href="product-detail.html" class="thumb c8" aria-label="Scented Candle Gift Set" style="background-image:url('https://grossiste-bougie.fr/images/p11-coffret.webp');background-size:cover;background-position:center">
              </a>
              <div class="card-body">
                <span class="tag">
                  Gift Sets
                </span>
                <h3>
                  <a href="product-detail.html">
                    Scented Candle Gift Set
                  </a>
                </h3>
                <div class="card-foot">
                  <span class="price">
                    $64.00
                  </span>
                  <a href="cart.html" class="btn btn-sm">
                    Add to cart
                  </a>
                </div>
              </div>
            </article>
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