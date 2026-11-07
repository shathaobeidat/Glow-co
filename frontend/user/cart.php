<?php
require_once __DIR__ . '/../../backend/includes/layout.php';
require_once __DIR__ . '/../../backend/classes/CartClass.php';

$cartobj = new Cart();
$error   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'add') {
        $error = $cartobj->add((int) ($_POST['product_id'] ?? 0), (int) ($_POST['qty'] ?? 1));
    } elseif ($action === 'update') {
        $error = $cartobj->update($_POST['qty'] ?? []);
    } elseif ($action === 'remove') {
        $cartobj->remove((int) ($_POST['product_id'] ?? 0));
    }

    // no error -> reload the page so refreshing does not repeat the action
    if ($error === '') {
        header('Location: cart.php');
        exit();
    }
}

$items = $cartobj->getitems();
$sum   = $cartobj->getsummary($items);

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
      Cart | Glow &amp; Co.
    </title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&amp;family=Jost:wght@300;400;500&amp;display=swap" rel="stylesheet">
    <!-- Shared styles (tokens, layout, components) -->
    <link rel="stylesheet" href="css/base.css">
    <!-- Page-specific styles -->
    <link rel="stylesheet" href="css/cart.css">
  </head>
  <body class="store">
    <?php render_header('cart'); ?>
    <main>
      <section class="page-head">
        <div class="container">
          <h1>
            Your cart
          </h1>
          <p>
            Review your items before checkout
          </p>
        </div>
      </section>
      <section class="section">
        <?php if ($error !== ''): ?>
        <div class="container">
          <div class="alert error" role="alert">
            <?= e($error) ?>
          </div>
        </div>
        <?php endif; ?>
        <?php if (!$items): ?>
        <div class="container" style="text-align:center">
          <p class="muted">
            Your cart is empty.
          </p>
          <p>
            <a href="products.php" class="btn">
              Continue shopping
            </a>
          </p>
        </div>
        <?php else: ?>
        <div class="container cart-grid">
          <div>
            <form action="cart.php" method="post">
              <input type="hidden" name="action" value="update">
              <div class="table-wrap">
                <table>
                  <thead>
                    <tr>
                      <th>
                        Product
                      </th>
                      <th>
                        Price
                      </th>
                      <th>
                        Quantity
                      </th>
                      <th>
                        Subtotal
                      </th>
                      <th>
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($items as $it): ?>
                    <tr>
                      <td>
                        <div class="item">
                          <span class="thumb sm c<?= (($it['id'] - 1) % 8) + 1 ?>"<?= image_style($it['image']) ?>>
                          </span>
                          <span>
                            <?= e($it['name']) ?>
                          </span>
                        </div>
                      </td>
                      <td>
                        <?= money($it['price']) ?>
                      </td>
                      <td>
                        <input type="number" name="qty[<?= $it['id'] ?>]" value="<?= $it['qty'] ?>" min="0" max="<?= max(1, $it['stock']) ?>" class="qty">
                      </td>
                      <td>
                        <?= money($it['subtotal']) ?>
                      </td>
                      <td>
                        <button type="submit" form="remove-<?= $it['id'] ?>" class="danger" style="background:none;border:0;cursor:pointer;font:inherit">
                          Remove
                        </button>
                      </td>
                    </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
              <button type="submit" class="btn btn-outline">
                Update cart
              </button>
            </form>
            <?php foreach ($items as $it): ?>
            <form id="remove-<?= $it['id'] ?>" action="cart.php" method="post">
              <input type="hidden" name="action" value="remove">
              <input type="hidden" name="product_id" value="<?= $it['id'] ?>">
            </form>
            <?php endforeach; ?>
          </div>
          <aside class="panel summary">
            <h2>
              Order summary
            </h2>
            <div class="line">
              <span>
                Subtotal
              </span>
              <span>
                <?= money($sum['subtotal']) ?>
              </span>
            </div>
            <div class="line">
              <span>
                Delivery
              </span>
              <span>
                <?= money($sum['delivery']) ?>
              </span>
            </div>
            <div class="line total">
              <span>
                Total
              </span>
              <span>
                <?= money($sum['total']) ?>
              </span>
            </div>
            <?php if (is_logged_in()): ?>
            <a href="checkout.php" class="btn btn-block">
              Proceed to checkout
            </a>
            <?php else: ?>
            <a href="checkout.php" class="btn btn-block">
              Login to checkout
            </a>
            <p class="muted" style="text-align:center;font-size:.85rem;margin-top:8px">
              Your cart is saved. You only need to log in to pay.
            </p>
            <?php endif; ?>
            <a href="products.php" class="link-center">
              Continue shopping
            </a>
          </aside>
        </div>
        <?php endif; ?>
      </section>
    </main>
    <?php render_footer(); ?>
  </body>
</html>