<?php require_once __DIR__ . '/../../backend/includes/layout.php'; ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
      Product Detail | Glow &amp; Co.
    </title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&amp;family=Jost:wght@300;400;500&amp;display=swap" rel="stylesheet">
    <!-- Shared styles (tokens, layout, components) -->
    <link rel="stylesheet" href="css/base.css">
    <!-- Page-specific styles -->
    <link rel="stylesheet" href="css/product-detail.css">
  </head>
  <body class="store">
         <?php render_header('product-detail'); ?>
    <main>
      <section class="section">
        <div class="container detail">
          <div class="detail-img thumb c1" style="background-image:url('https://stoaparis.com/cdn/shop/files/1_3335af10-bb6e-4c55-ae71-e954b96ba81d.jpg?v=1758781146');background-size:cover;background-position:center">
          </div>
          <div>
            <span class="tag">
              Scented Candles
            </span>
            <h1>
              Vanilla &amp; Lavender Candle
            </h1>
            <p class="price big">
              $24.00
            </p>
            <p class="muted">
              A calming blend of creamy vanilla and soft lavender, hand-poured in natural soy wax with a cotton wick. Burns for around 40 hours.
            </p>
            <p>
              <span class="badge ok">
                In stock: 18
              </span>
            </p>
            <form action="cart.html" method="post" class="buy">
              <label for="qty">
                Quantity
              </label>
              <input type="number" id="qty" name="qty" value="1" min="1" max="18">
              <button type="submit" class="btn">
                Add to cart
              </button>
            </form>
            <ul class="specs">
              <li>
                Weight: 220 g
              </li>
              <li>
                Vessel: reusable glass jar
              </li>
              <li>
                100% soy wax
              </li>
            </ul>
          </div>
        </div>
      </section>
      <section class="section alt">
        <div class="container">
          <div class="sec-head">
            <h2>
              You may also like
            </h2>
          </div>
          <div class="grid grid-4">
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
          </div>
        </div>
      </section>
    </main>
     <?php render_footer(); ?>
  </body>
</html>
