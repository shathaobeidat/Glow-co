<?php
require_once __DIR__ . '/../../backend/includes/admin-layout.php';
require_once __DIR__ . '/../../backend/classes/OrdersClass.php';

require_admin();

$orderobj = new OrdersClass();
$stats    = $orderobj->getstats();
$orders   = $orderobj->getlatest(5);

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
      Admin Dashboard | Glow &amp; Co.
    </title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&amp;family=Jost:wght@300;400;500&amp;display=swap" rel="stylesheet">

    <link rel="stylesheet" href="css/base.css">
    <link rel="stylesheet" href="css/admin-dashboard.css">
  </head>
  <body class="admin">
    <?php render_admin_header('dashboard'); ?>
        <div class="a-head">
          <h1>
            Dashboard
          </h1>
        </div>
        <div class="metrics">
          <div class="metric">
            <small>
              Paid revenue
            </small>
            <strong>
              JOD <?= number_format($stats['revenue'], 2) ?>
            </strong>
          </div>
          <div class="metric">
            <small>
              Orders
            </small>
            <strong>
              <?= (int) $stats['orders'] ?>
            </strong>
          </div>
          <div class="metric">
            <small>
              Products
            </small>
            <strong>
              <?= (int) $stats['products'] ?>
            </strong>
          </div>
          <div class="metric">
            <small>
              Customers
            </small>
            <strong>
              <?= (int) $stats['customers'] ?>
            </strong>
          </div>
        </div>
        <div class="panel">
          <h2>
            Latest orders
          </h2>
          <div class="table-wrap">
            <table>
              <thead>
                <tr>
                  <th>
                    Order
                  </th>
                  <th>
                    Customer
                  </th>
                  <th>
                    Total
                  </th>
                  <th>
                    Payment
                  </th>
                  <th>
                    Status
                  </th>
                </tr>
              </thead>
              <tbody>
                <?php if (empty($orders)): ?>
                <tr>
                  <td colspan="5">
                    No orders yet.
                  </td>
                </tr>
                <?php else: ?>
                <?php foreach ($orders as $order): ?>
                <tr>
                  <td>
                    <a href="admin-order-detail.php?id=<?= (int) $order['id'] ?>">
                      #<?= (int) $order['id'] ?>
                    </a>
                  </td>
                  <td>
                    <?= e($order['shipping_name']) ?>
                  </td>
                  <td>
                    JOD <?= number_format($order['total'], 2) ?>
                  </td>
                  <td>
                    <span class="<?= OrdersClass::badgeclass($order['payment_status']) ?>">
                      <?= e($order['payment_status']) ?>
                    </span>
                  </td>
                  <td>
                    <span class="<?= OrdersClass::badgeclass($order['status']) ?>">
                      <?= e($order['status']) ?>
                    </span>
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