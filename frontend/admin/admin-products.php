<?php
require_once __DIR__ . '/../../backend/includes/admin-layout.php';
require_once __DIR__ . '/../../backend/classes/ProductClass.php';

require_admin();

$productobj = new Product();
$error      = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_product'])) {
    if (!verify_csrf()) {
        $error = 'Invalid request. Please try again.';
    } else {
        $productobj->delete((int) ($_POST['id']));
        header('Location: admin-products.php?deleted=1');
        exit();
    }
}

$saved    = isset($_GET['saved']);
$deleted  = isset($_GET['deleted']);
$products = $productobj->getproducts();

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
      Admin Products | Glow &amp; Co.
    </title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&amp;family=Jost:wght@300;400;500&amp;display=swap" rel="stylesheet">

    <link rel="stylesheet" href="css/base.css">
    <link rel="stylesheet" href="css/admin-products.css">
  </head>
  <body class="admin">
    <?php render_admin_header('products'); ?>
        <div class="a-head">
          <h1>
            Products
          </h1>
          <a href="admin-product-form.php" class="btn">
            + Add product
          </a>
        </div>
        <?php if ($saved): ?>
        <div class="alert success" role="status">
          Product saved successfully.
        </div>
        <?php endif; ?>
        <?php if ($deleted): ?>
        <div class="alert success" role="status">
          Product deleted.
        </div>
        <?php endif; ?>
        <?php if ($error !== ''): ?>
        <div class="alert error" role="alert">
          <?= e($error) ?>
        </div>
        <?php endif; ?>
        <div class="panel">
          <div class="table-wrap">
            <table>
              <thead>
                <tr>
                  <th>
                    Image
                  </th>
                  <th>
                    Name
                  </th>
                  <th>
                    Category
                  </th>
                  <th>
                    Price
                  </th>
                  <th>
                    Stock
                  </th>
                  <th>
                    Actions
                  </th>
                </tr>
              </thead>
              <tbody>
                <?php if (empty($products)): ?>
                <tr>
                  <td colspan="6">
                    No products yet.
                  </td>
                </tr>
                <?php else: ?>
                <?php foreach ($products as $product): ?>
                <tr>
                  <td>
                    <span class="thumb sm"<?= image_style($product['image']) ?>>
                    </span>
                  </td>
                  <td>
                    <?= e($product['name']) ?>
                  </td>
                  <td>
                    <?= e($product['category_name']) ?>
                  </td>
                  <td>
                    JOD <?= number_format($product['price'], 2) ?>
                  </td>
                  <td>
                    <?= (int) $product['stock'] ?>
                  </td>
                  <td class="acts">
                    <a href="admin-product-form.php?id=<?= (int) $product['id'] ?>">
                      Edit
                    </a>
                    <form action="admin-products.php" method="post" onsubmit="return confirm('Delete this product?');">
                      <?= csrf_field() ?>
                      <input type="hidden" name="id" value="<?= (int) $product['id'] ?>">
                      <button type="submit" name="delete_product" class="link-btn danger">
                        Delete
                      </button>
                    </form>
                  </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
    <?php render_admin_footer(); ?>
  </body>
</html>