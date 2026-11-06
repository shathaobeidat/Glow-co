<?php require_once __DIR__ . '/../../backend/includes/layout.php'; ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
      Shop | Glow &amp; Co.
    </title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&amp;family=Jost:wght@300;400;500&amp;display=swap" rel="stylesheet">
    <!-- Shared styles (tokens, layout, components) -->
    <link rel="stylesheet" href="css/base.css">
    <!-- Page-specific styles -->
    <link rel="stylesheet" href="css/products.css">
  </head>
  <body class="store">
         <?php render_header('products'); ?>
    <main>
      <section class="page-head">
        <div class="container">
          <h1>
            Shop all products
          </h1>
          <p>
            Search by title or filter by category
          </p>
        </div>
      </section>
      <section class="section">
        <div class="container">
          <form class="filters" action="#" method="get">
            <input type="search" name="q" placeholder="Search products by title...">
            <select name="category">
              <option value="">
                All categories
              </option>
              <option>
                Scented Candles
              </option>
              <option>
                Diffusers & Essential Oils
              </option>
              <option>
                Trays & Coasters
              </option>
              <option>
                Gift Sets
              </option>
            </select>
            <button type="submit" class="btn">
              Search
            </button>
          </form>
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
    </main>
    <?php render_footer(); ?>
  </body>
</html>
