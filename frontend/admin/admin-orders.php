<?php
require_once __DIR__ . '/../../backend/includes/admin-layout.php';
require_once __DIR__ . '/../../backend/classes/AdminOrdersClass.php';
require_once __DIR__ . '/../../backend/classes/OrdersClass.php';

require_admin();

$orders = (new AdminOrdersClass())->getall();

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
      Admin Orders | Glow &amp; Co.
    </title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&amp;family=Jost:wght@300;400;500&amp;display=swap" rel="stylesheet">

    <link rel="stylesheet" href="css/base.css">
    <link rel="stylesheet" href="css/admin-orders.css">
  </head>
  <body class="admin">
    <?php render_admin_header('orders'); ?>
        <div class="a-head">
          <h1>
            Orders
          </h1>
        </div>
        <div class="panel">
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
                    Date
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
                  <th>
                  </th>
                </tr>
              </thead>
              <tbody>
                <?php if (empty($orders)): ?>
                <tr>
                  <td colspan="7">
                    No orders yet.
                  </td>
                </tr>
                <?php else: ?>
                <?php foreach ($orders as $order): ?>
                <tr>
                  <td>
                    #<?= (int) $order['id'] ?>
                  </td>
                  <td>
                    <?= e($order['shipping_name']) ?>
                  </td>
                  <td>
                    <?= e(date('d M Y', strtotime($order['created_at']))) ?>
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
                  <td>
                    <a href="admin-order-detail.php?id=<?= (int) $order['id'] ?>">
                      Inspect
                    </a>
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