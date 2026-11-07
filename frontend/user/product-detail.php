<?php
require_once __DIR__ . '/../../backend/includes/layout.php';
require_once __DIR__ . '/../../backend/classes/ProductClass.php';

$productobj = new Product();
$id         = (int) ($_GET['id'] ?? 0);
$product    = $productobj->getone($id);

if (!$product) {
    header('Location: products.php');
    exit();
}

$related = $productobj->getrelated($id, $product['category_id'], 4);

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
      <?= e($product['name']) ?> | Glow &amp; Co.
    </title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&amp;family=Jost:wght@300;400;500&amp;display=swap" rel="stylesheet">
    <!-- Shared styles (tokens, layout, components) -->
    <link rel="stylesheet" href="css/base.css">
    <!-- Page-specific styles -->
    <link rel="stylesheet" href="css/product-detail.css">
  </head>
  <body class="store">
    <?php render_header('shop'); ?>
    <main>
      <section class="section">
        <div class="container detail">
          <div class="detail-img thumb c<?= (($product['id'] - 1) % 8) + 1 ?>"<?= image_style($product['image']) ?>>
          </div>
          <div>
            <span class="tag">
              <?= e($product['category_name']) ?>
            </span>
            <h1>
              <?= e($product['name']) ?>
            </h1>
            <p class="price big">
              <?= money($product['price']) ?>
            </p>
            <p class="muted">
              <?= nl2br(e((string) $product['description'])) ?>
            </p>
            <p>
              <?php if ($product['stock'] > 0): ?>
              <span class="badge ok">
                In stock: <?= $product['stock'] ?>
              </span>
              <?php else: ?>
              <span class="badge warn">
                Out of stock
              </span>
              <?php endif; ?>
            </p>
            <?php if ($product['stock'] > 0): ?>
            <form action="cart.php" method="post" class="buy">
              <input type="hidden" name="action" value="add">
              <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
              <label for="qty">
                Quantity
              </label>
              <input type="number" id="qty" name="qty" value="1" min="1" max="<?= $product['stock'] ?>">
              <button type="submit" class="btn">
                Add to cart
              </button>
            </form>
            <?php endif; ?>
          </div>
        </div>
      </section>
      <?php if ($related): ?>
      <section class="section alt">
        <div class="container">
          <div class="sec-head">
            <h2>
              You may also like
            </h2>
          </div>
          <div class="grid grid-4">
            <?php foreach ($related as $p): render_product_card($p); endforeach; ?>
          </div>
        </div>
      </section>
      <?php endif; ?>
    </main>
    <?php render_footer(); ?>
  </body>
</html>