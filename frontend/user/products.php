<?php
require_once __DIR__ . '/../../backend/includes/layout.php';
require_once __DIR__ . '/../../backend/classes/ProductsClass.php';

$productobj = new ProductsClass();
$search     = trim($_GET['q'] ?? '');
$categoryId = (int) ($_GET['category'] ?? 0);
$categories = $productobj->getcategories();
$products   = $productobj->getproducts($search, $categoryId);

?>
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

    <link rel="stylesheet" href="css/base.css">
    <link rel="stylesheet" href="css/products.css">
  </head>
  <body class="store">
    <?php render_header('shop'); ?>
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
          <form class="filters" action="products.php" method="get">
            <input type="search" name="q" placeholder="Search products by title..." value="<?= e($search) ?>">
            <select name="category">
              <option value="">
                All categories
              </option>
              <?php foreach ($categories as $cat): ?>
              <option value="<?= (int) $cat['id'] ?>"<?= $categoryId === $cat['id'] ? ' selected' : '' ?>>
                <?= e($cat['name']) ?>
              </option>
              <?php endforeach; ?>
            </select>
            <button type="submit" class="btn">
              Search
            </button>
          </form>

          <?php if (empty($products)): ?>
          <div class="alert error" role="status">
            No products found.
          </div>
          <?php else: ?>
          <div class="grid grid-4">
            <?php foreach ($products as $product): ?>
            <article class="card">
              <a href="product-detail.php?id=<?= (int) $product['id'] ?>" class="thumb" aria-label="<?= e($product['name']) ?>"<?= $product['image'] !== '' ? ' style="background-image:url(\'' . e($product['image']) . '\');background-size:cover;background-position:center"' : '' ?>>
              </a>
              <div class="card-body">
                <?php if ($product['category_name'] !== ''): ?>
                <span class="tag">
                  <?= e($product['category_name']) ?>
                </span>
                <?php endif; ?>
                <h3>
                  <a href="product-detail.php?id=<?= (int) $product['id'] ?>">
                    <?= e($product['name']) ?>
                  </a>
                </h3>
                <div class="card-foot">
                  <span class="price">
                    JOD <?= number_format($product['price'], 2) ?>
                  </span>
                  <?php if ($product['stock'] > 0): ?>
                  <a href="cart.php?add=<?= (int) $product['id'] ?>" class="btn btn-sm">
                    Add to cart
                  </a>
                  <?php else: ?>
                  <span class="badge warn">
                    Out of stock
                  </span>
                  <?php endif; ?>
                </div>
              </div>
            </article>
            <?php endforeach; ?>
          </div>
          <?php endif; ?>
        </div>
      </section>
    </main>
    <?php render_footer(); ?>
  </body>
</html>