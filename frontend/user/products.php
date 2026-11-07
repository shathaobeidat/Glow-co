<?php
require_once __DIR__ . '/../../backend/includes/layout.php';
require_once __DIR__ . '/../../backend/classes/ProductClass.php';
require_once __DIR__ . '/../../backend/classes/CategoryClass.php';

$productobj  = new Product();
$categoryobj = new Category();

$search     = trim($_GET['q'] ?? '');
$categoryId = (int) ($_GET['category'] ?? 0);
$perPage    = 8;
$page       = max(1, (int) ($_GET['page'] ?? 1));

$total = $productobj->countproducts($search, $categoryId);
$pages = max(1, (int) ceil($total / $perPage));
if ($page > $pages) {
    $page = $pages;
}

$products   = $productobj->getproducts($search, $categoryId, $perPage, ($page - 1) * $perPage);
$categories = $categoryobj->getallcategories();
$hasFilters = ($search !== '' || $categoryId > 0);

// Builds the link of a pagination button and keeps the search/category filters
function page_url(int $page, string $search, int $categoryId): string
{
    $params = ['page' => $page];
    if ($search !== '') {
        $params['q'] = $search;
    }
    if ($categoryId > 0) {
        $params['category'] = $categoryId;
    }
    return 'products.php?' . http_build_query($params);
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop | Glow &amp; Co.</title>
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
          <h1>Shop all products</h1>
          <p>Search by title or filter by category</p>
        </div>
      </section>

      <section class="section">
        <div class="container">
          <form class="filters" action="products.php" method="get">
            <input type="search" name="q" placeholder="Search products by title..." value="<?= e($search) ?>" maxlength="100" aria-label="Search products">
            <select name="category" aria-label="Category">
              <option value="">All categories</option>
              <?php foreach ($categories as $cat): ?>
              <option value="<?= (int) $cat['id'] ?>"<?= $categoryId === (int) $cat['id'] ? ' selected' : '' ?>>
                <?= e((string) $cat['name']) ?>
              </option>
              <?php endforeach; ?>
            </select>
            <button type="submit" class="btn">Search</button>
          </form>

          <div class="results-bar">
            <span class="muted">
              <?= $total ?> product<?= $total === 1 ? '' : 's' ?> found
            </span>
            <?php if ($hasFilters): ?>
            <a href="products.php" class="clear-link">Clear filters</a>
            <?php endif; ?>
          </div>

          <?php if (empty($products)): ?>
          <div class="empty-state" role="status">
            <p>No products found.</p>
            <?php if ($hasFilters): ?>
            <a href="products.php" class="btn btn-outline">View all products</a>
            <?php endif; ?>
          </div>
          <?php else: ?>
          <div class="grid grid-4">
            <?php foreach ($products as $product): render_product_card($product); endforeach; ?>
          </div>

          <?php if ($pages > 1): ?>
          <nav class="pagination" aria-label="Pagination">
            <?php if ($page > 1): ?>
            <a href="<?= e(page_url($page - 1, $search, $categoryId)) ?>" class="pg">&laquo; Prev</a>
            <?php endif; ?>
            <?php for ($i = 1; $i <= $pages; $i++): ?>
            <a href="<?= e(page_url($i, $search, $categoryId)) ?>" class="pg<?= $i === $page ? ' current' : '' ?>"<?= $i === $page ? ' aria-current="page"' : '' ?>><?= $i ?></a>
            <?php endfor; ?>
            <?php if ($page < $pages): ?>
            <a href="<?= e(page_url($page + 1, $search, $categoryId)) ?>" class="pg">Next &raquo;</a>
            <?php endif; ?>
          </nav>
          <?php endif; ?>
          <?php endif; ?>
        </div>
      </section>
    </main>
    <?php render_footer(); ?>
  </body>
</html>