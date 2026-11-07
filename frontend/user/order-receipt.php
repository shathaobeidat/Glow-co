<?php
require_once __DIR__ . '/../../backend/includes/layout.php';
require_once __DIR__ . '/../../backend/classes/OrdersClass.php';

if (!is_logged_in()) {
    header('Location: ' . SITE_URL . '/backend/auth/login.php');
    exit();
}

$orderobj = new OrdersClass();
$order    = $orderobj->getuserorder((int) ($_GET['id'] ?? 0), (int) $_SESSION['user_id']);

if (!$order) {
    header('Location: profile.php');
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
      Order receipt | Glow &amp; Co.
    </title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&amp;family=Jost:wght@300;400;500&amp;display=swap" rel="stylesheet">
    <!-- Shared styles (tokens, layout, components) -->
    <link rel="stylesheet" href="css/base.css">
    <!-- Page-specific styles -->
    <link rel="stylesheet" href="css/order-receipt.css">
  </head>
  <body class="store">
    <?php render_header('profile'); ?>
    <main>
      <section class="section">
        <div class="container narrow">
          <div class="panel receipt">
            <div class="check">
              &#10003;
            </div>
            <h1>
              Thank you, your order is placed!
            </h1>
            <p class="muted">
              Order #<?= (int) $order['id'] ?> &bull; <?= e(date('d M Y', strtotime($order['created_at']))) ?>
            </p>
            <div class="status-row">
              <div>
                <small>
                  Payment method
                </small>
                <span class="badge">
                  <?= e(strtoupper($order['payment_method'])) ?>
                </span>
              </div>
              <div>
                <small>
                  Order status
                </small>
                <span class="<?= OrdersClass::badgeclass($order['status']) ?>">
                  <?= e(ucfirst($order['status'])) ?>
                </span>
              </div>
            </div>
            <div class="table-wrap">
              <table>
                <thead>
                  <tr>
                    <th>
                      Item
                    </th>
                    <th>
                      Price
                    </th>
                    <th>
                      Qty
                    </th>
                    <th>
                      Subtotal
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($order['items'] as $it): ?>
                  <tr>
                    <td>
                      <?= e($it['name']) ?>
                    </td>
                    <td>
                      <?= money((float) $it['price']) ?>
                    </td>
                    <td>
                      <?= (int) $it['qty'] ?>
                    </td>
                    <td>
                      <?= money((float) $it['subtotal']) ?>
                    </td>
                  </tr>
                  <?php endforeach; ?>
                  <tr>
                    <td colspan="3">
                      Delivery
                    </td>
                    <td>
                      <?= money((float) $order['delivery_fee']) ?>
                    </td>
                  </tr>
                  <tr>
                    <td colspan="3">
                      <strong>
                        Total to pay on delivery
                      </strong>
                    </td>
                    <td>
                      <strong>
                        <?= money((float) $order['total']) ?>
                      </strong>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <h3>
              Delivering to
            </h3>
            <p class="muted">
              <?= e($order['shipping_name']) ?>, <?= e($order['shipping_address']) ?>, <?= e($order['shipping_city']) ?> &bull; <?= e($order['shipping_phone']) ?>
            </p>
            <p>
              <a href="products.php" class="btn">
                Continue shopping
              </a>
            </p>
          </div>
        </div>
      </section>
    </main>
    <?php render_footer(); ?>
  </body>
</html>