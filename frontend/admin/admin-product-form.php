<?php
require_once __DIR__ . '/../../backend/includes/admin-layout.php';
require_once __DIR__ . '/../../backend/classes/AdminProductsClass.php';
require_once __DIR__ . '/../../backend/classes/AdminCategoriesClass.php';
require_once __DIR__ . '/../../backend/classes/ImageUploadClass.php';
require_once __DIR__ . '/../../backend/classes/ProductsClass.php';

require_admin();

$productobj  = new AdminProductsClass();
$categoryobj = new AdminCategoriesClass();
$categories  = $categoryobj->getall();

$id      = (int) ($_GET['id'] ?? 0);
$product = $id > 0 ? $productobj->getone($id) : false;

if ($id > 0 && !$product) {
    header('Location: admin-products.php');
    exit();
}

$currentImage = $product ? $product['image'] : '';
$error        = '';
$form         = [
    'name'        => $product ? $product['name'] : '',
    'description' => $product ? $product['description'] : '',
    'price'       => $product ? number_format($product['price'], 2, '.', '') : '',
    'stock'       => $product ? (string) $product['stock'] : '',
    'category_id' => $product ? $product['category_id'] : 0,
];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_product'])) {
    $form['name']        = trim($_POST['name'] ?? '');
    $form['description'] = trim($_POST['description'] ?? '');
    $form['price']       = trim($_POST['price'] ?? '');
    $form['stock']       = trim($_POST['stock'] ?? '');
    $form['category_id'] = (int) ($_POST['category'] ?? 0);

    if (!verify_csrf()) {
        $error = 'Invalid request. Please try again.';
    } elseif ($form['name'] === '' || $form['description'] === '' || $form['price'] === '' || $form['stock'] === '') {
        $error = 'All fields are required';
    } elseif (mb_strlen($form['name']) > 150) {
        $error = 'Product name is too long';
    } elseif (!is_numeric($form['price']) || (float) $form['price'] < 0) {
        $error = 'Invalid price';
    } elseif (!ctype_digit($form['stock'])) {
        $error = 'Invalid stock quantity';
    } elseif (!$categoryobj->getone($form['category_id'])) {
        $error = 'Please choose a category';
    } else {
        try {
            $uploader = new ImageUploadClass();
            $newImage = $uploader->save($_FILES['image'] ?? [], 'products');

            if ($id > 0) {
                $productobj->update($id, $form['category_id'], $form['name'], $form['description'], (float) $form['price'], (int) $form['stock'], $newImage);
                if ($newImage !== '' && $currentImage !== '') {
                    $uploader->delete($currentImage);
                }
            } else {
                $productobj->create($form['category_id'], $form['name'], $form['description'], (float) $form['price'], (int) $form['stock'], $newImage);
            }

            header('Location: admin-products.php?saved=1');
            exit();
        } catch (RuntimeException $ex) {
            $error = $ex->getMessage();
        } catch (Throwable $ex) {
            $error = 'Something went wrong while saving the product. Please try again.';
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
      Admin Product Form | Glow &amp; Co.
    </title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&amp;family=Jost:wght@300;400;500&amp;display=swap" rel="stylesheet">

    <link rel="stylesheet" href="css/base.css">
    <link rel="stylesheet" href="css/admin-product-form.css">
  </head>
  <body class="admin">
    <?php render_admin_header('products'); ?>
        <div class="a-head">
          <h1>
            <?= $id > 0 ? 'Edit product' : 'Add product' ?>
          </h1>
        </div>
        <form class="panel form-narrow" action="admin-product-form.php<?= $id > 0 ? '?id=' . $id : '' ?>" method="post" enctype="multipart/form-data" novalidate>
          <?= csrf_field() ?>
          <?php if ($error !== ''): ?>
          <div class="alert error" role="alert">
            <?= e($error) ?>
          </div>
          <?php endif; ?>
          <div class="field">
            <label for="name">
              Name
            </label>
            <input type="text" id="name" name="name" placeholder="Product name" value="<?= e($form['name']) ?>" required>
          </div>
          <div class="field">
            <label for="description">
              Description
            </label>
            <textarea id="description" name="description" rows="4" required><?= e($form['description']) ?></textarea>
          </div>
          <div class="two">
            <div class="field">
              <label for="price">
                Price (JOD)
              </label>
              <input type="number" id="price" name="price" placeholder="0.00" step="0.01" min="0" value="<?= e($form['price']) ?>" required>
            </div>
            <div class="field">
              <label for="stock">
                Stock
              </label>
              <input type="number" id="stock" name="stock" placeholder="0" min="0" value="<?= e($form['stock']) ?>" required>
            </div>
          </div>
          <div class="field">
            <label for="category">
              Category
            </label>
            <select id="category" name="category" required>
              <option value="">
                Choose a category
              </option>
              <?php foreach ($categories as $cat): ?>
              <option value="<?= (int) $cat['id'] ?>"<?= $form['category_id'] === $cat['id'] ? ' selected' : '' ?>>
                <?= e($cat['name']) ?>
              </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="field">
            <label for="image">
              Product image
            </label>
            <input type="file" id="image" name="image" accept=".jpg,.jpeg,.png,.webp">
            <small class="muted">
              JPG, PNG or WEBP, max 2 MB.<?= $currentImage !== '' ? ' Leave empty to keep the current image.' : '' ?>
            </small>
          </div>
          <?php if ($currentImage !== ''): ?>
          <div class="preview">
            <span class="thumb sm"<?= ProductsClass::imagestyle($currentImage) ?>>
            </span>
            <small class="muted">
              Current image
            </small>
          </div>
          <?php endif; ?>
          <div class="acts-row">
            <button type="submit" name="save_product" class="btn">
              Save product
            </button>
            <a href="admin-products.php" class="btn btn-outline">
              Cancel
            </a>
          </div>
        </form>
    <?php render_admin_footer(); ?>
  </body>
</html>