<?php
require_once __DIR__ . '/../../backend/includes/admin-layout.php';
require_once __DIR__ . '/../../backend/classes/CategoryClass.php';

require_admin();

$categoryobj = new Category();
$error       = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_category'])) {
    $deleteId = (int) ($_POST['id'] );

    if (!verify_csrf()) {
        $error = 'Invalid request. Please try again.';
    } elseif ($categoryobj->countproducts($deleteId) > 0) {
        $error = 'This category still has products. Move or delete them first.';
    } else {
        $categoryobj->delete($deleteId);
        header('Location: admin-categories.php?deleted=1');
        exit();
    }
}

$saved      = isset($_GET['saved']);
$deleted    = isset($_GET['deleted']);
$categories = $categoryobj->getallcategories();

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
      Admin Categories | Glow &amp; Co.
    </title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&amp;family=Jost:wght@300;400;500&amp;display=swap" rel="stylesheet">

    <link rel="stylesheet" href="css/base.css">
    <link rel="stylesheet" href="css/admin-categories.css">
  </head>
  <body class="admin">
    <?php render_admin_header('categories'); ?>
        <div class="a-head">
          <h1>
            Categories
          </h1>
          <a href="admin-category-form.php" class="btn">
            + Add category
          </a>
        </div>
        <?php if ($saved): ?>
        <div class="alert success" role="status">
          Category saved successfully.
        </div>
        <?php endif; ?>
        <?php if ($deleted): ?>
        <div class="alert success" role="status">
          Category deleted.
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
                    Description
                  </th>
                  <th>
                    Actions
                  </th>
                </tr>
              </thead>
              <tbody>
                <?php if (empty($categories)): ?>
                <tr>
                  <td colspan="4">
                    No categories yet.
                  </td>
                </tr>
                <?php else: ?>
                <?php foreach ($categories as $cat): ?>
                <tr>
                  <td>
                    <span class="thumb sm"<?= image_style($cat['image']) ?>>
                    </span>
                  </td>
                  <td>
                    <?= e($cat['name']) ?>
                  </td>
                  <td>
                    <?= e($cat['description']) ?>
                  </td>
                  <td class="acts">
                    <a href="admin-category-form.php?id=<?= (int) $cat['id'] ?>">
                      Edit
                    </a>
                    <form action="admin-categories.php" method="post" onsubmit="return confirm('Delete this category?');">
                      <?= csrf_field() ?>
                      <input type="hidden" name="id" value="<?= (int) $cat['id'] ?>">
                      <button type="submit" name="delete_category" class="link-btn danger">
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