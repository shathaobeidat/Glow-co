<?php
require_once __DIR__ . '/../../backend/includes/admin-layout.php';
require_once __DIR__ . '/../../backend/classes/OrdersClass.php';

require_admin();

$orderobj = new OrdersClass();
$id       = (int) ($_GET['id'] ?? 0);
$order    = $orderobj->getorder($id);

if (!$order) {
    header('Location: admin-orders.php');
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_order'])) {
    if (!verify_csrf()) {
        $error = 'Invalid request. Please try again.';
    } elseif ($orderobj->updateorder($id, (string) ($_POST['status'] ?? ''), (string) ($_POST['payment'] ?? ''))) {
        header('Location: admin-order-detail.php?id=' . $id . '&updated=1');
        exit();
    } else {
        $error = 'Could not update this order. Cancelled orders cannot be changed.';
    }
}

$updated = isset($_GET['updated']);
$items   = $orderobj->getorderitems($id);
$order   = $orderobj->getorder($id);      // read again to show the new values
$locked  = $order['status'] === 'cancelled';

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
      Admin Order Detail | Glow &amp; Co.
    </title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&amp;family=Jost:wght@300;400;500&amp;display=swap" rel="stylesheet">

    <link rel="stylesheet" href="css/base.css">
    <link rel="stylesheet" href="css/admin-order-detail.css">
  </head>
  <body class="admin">
    <?php render_admin_header('orders'); ?>
        <div class="a-head">
          <h1>
            Order #<?= (int) $order['id'] ?>
          </h1>
          <a href="admin-orders.php" class="btn btn-outline">
            &larr; All orders
          </a>
        </div>
        <?php if ($updated): ?>
        <div class="alert success" role="status">
          Order updated successfully.
        </div>
        <?php endif; ?>
        <?php if ($error !== ''): ?>
        <div class="alert error" role="alert">
          <?= e($error) ?>
        </div>
        <?php endif; ?>
        <div class="detail-grid">
          <div class="panel">
            <h2>
              Ordered items
            </h2>
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
                  <?php foreach ($items as $item): ?>
                  <tr>
                    <td>
                      <?= e($item['name']) ?>
                    </td>
                    <td>
                      JOD <?= number_format($item['price'], 2) ?>
                    </td>
                    <td>
                      <?= (int) $item['quantity'] ?>
                    </td>
                    <td>
                      JOD <?= number_format($item['subtotal'], 2) ?>
                    </td>
                  </tr>
                  <?php endforeach; ?>
                  <tr>
                    <td colspan="3">
                      <strong>
                        Total (incl. JOD <?= number_format($order['delivery_fee'], 2) ?> delivery)
                      </strong>
                    </td>
                    <td>
                      <strong>
                        JOD <?= number_format($order['total'], 2) ?>
                      </strong>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <h3>
              Customer
            </h3>
            <p class="muted">
              <?= e($order['shipping_name']) ?>
              <br>
              <?= e($order['shipping_address']) ?>, <?= e($order['shipping_city']) ?><?= $order['shipping_zip'] !== '' ? ' ' . e($order['shipping_zip']) : '' ?>
              <br>
              <?= e($order['shipping_phone']) ?>
            </p>
            <?php if ($order['notes'] !== ''): ?>
            <h3>
              Order notes
            </h3>
            <p class="muted">
              <?= nl2br(e($order['notes'])) ?>
            </p>
            <?php endif; ?>
          </div>
          <form class="panel" action="admin-order-detail.php?id=<?= (int) $order['id'] ?>" method="post">
            <?= csrf_field() ?>
            <h2>
              Update order
            </h2>
            <div class="field">
              <label for="status">
                Order status
              </label>
              <select id="status" name="status"<?= $locked ? ' disabled' : '' ?>>
                <?php foreach (OrdersClass::STATUSES as $status): ?>
                <option value="<?= e($status) ?>"<?= $order['status'] === $status ? ' selected' : '' ?>>
                  <?= e(ucfirst($status)) ?>
                </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="field">
              <label for="payment">
                Payment status
              </label>
              <select id="payment" name="payment"<?= $locked ? ' disabled' : '' ?>>
                <?php foreach (OrdersClass::PAYMENTS as $payment): ?>
                <option value="<?= e($payment) ?>"<?= $order['payment_status'] === $payment ? ' selected' : '' ?>>
                  <?= e(ucfirst($payment)) ?>
                </option>
                <?php endforeach; ?>
              </select>
            </div>
            <p class="muted">
              Payment method: <?= $order['payment_method'] === 'cod' ? 'Cash on Delivery' : e(strtoupper($order['payment_method'])) ?>
            </p>
            <?php if ($locked): ?>
            <p class="muted">
              This order is cancelled and can no longer be changed.
            </p>
            <?php else: ?>
            <button type="submit" name="save_order" class="btn btn-block">
              Save changes
            </button>
            <?php endif; ?>
          </form>
        </div>
    <?php render_admin_footer(); ?>
  </body>
</html>