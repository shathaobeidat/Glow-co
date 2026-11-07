<?php
require_once __DIR__ . '/../../backend/includes/admin-layout.php';
require_once __DIR__ . '/../../backend/classes/AdminCategoriesClass.php';
require_once __DIR__ . '/../../backend/classes/ImageUploadClass.php';
require_once __DIR__ . '/../../backend/classes/ProductsClass.php';

require_admin();

$categoryobj = new AdminCategoriesClass();

$id       = (int) ($_GET['id'] ?? 0);
$category = $id > 0 ? $categoryobj->getone($id) : false;

if ($id > 0 && !$category) {
    header('Location: admin-categories.php');
    exit();
}

$currentImage = $category ? $category['image'] : '';
$error        = '';
$form         = [
    'name'        => $category ? $category['name'] : '',
    'description' => $category ? $category['description'] : '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_category'])) {
    $form['name']        = trim($_POST['name'] ?? '');
    $form['description'] = trim($_POST['description'] ?? '');

    if (!verify_csrf()) {
        $error = 'Invalid request. Please try again.';
    } elseif ($form['name'] === '') {
        $error = 'Category name is required';
    } elseif (mb_strlen($form['name']) > 100 || mb_strlen($form['description']) > 255) {
        $error = 'Name or description is too long';
    } else {
        try {
            $uploader = new ImageUploadClass();
            $newImage = $uploader->save($_FILES['image'] ?? [], 'categories');

            if ($id > 0) {
                $categoryobj->update($id, $form['name'], $form['description'], $newImage);
                if ($newImage !== '' && $currentImage !== '') {
                    $uploader->delete($currentImage);
                }
            } else {
                $categoryobj->create($form['name'], $form['description'], $newImage);
            }

            header('Location: admin-categories.php?saved=1');
            exit();
        } catch (RuntimeException $ex) {
            $error = $ex->getMessage();
        } catch (Throwable $ex) {
            $error = 'Something went wrong while saving the category. Please try again.';
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
      Admin Category Form | Glow &amp; Co.
    </title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&amp;family=Jost:wght@300;400;500&amp;display=swap" rel="stylesheet">

    <link rel="stylesheet" href="css/base.css">
    <link rel="stylesheet" href="css/admin-category-form.css">
  </head>
  <body class="admin">
    <?php render_admin_header('categories'); ?>
        <div class="a-head">
          <h1>
            <?= $id > 0 ? 'Edit category' : 'Add category' ?>
          </h1>
        </div>
        <form class="panel form-narrow" action="admin-category-form.php<?= $id > 0 ? '?id=' . $id : '' ?>" method="post" enctype="multipart/form-data" novalidate>
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
            <input type="text" id="name" name="name" placeholder="Category name" value="<?= e($form['name']) ?>" required>
          </div>
          <div class="field">
            <label for="description">
              Description
            </label>
            <textarea id="description" name="description" rows="4"><?= e($form['description']) ?></textarea>
          </div>
          <div class="field">
            <label for="image">
              Category image
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
            <button type="submit" name="save_category" class="btn">
              Save category
            </button>
            <a href="admin-categories.php" class="btn btn-outline">
              Cancel
            </a>
          </div>
        </form>
    <?php render_admin_footer(); ?>
  </body>
</html>