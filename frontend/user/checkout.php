<?php
require_once __DIR__ . '/../../backend/includes/layout.php';
require_once __DIR__ . '/../../backend/classes/CartClass.php';
require_once __DIR__ . '/../../backend/classes/OrdersClass.php';

// guests must log in first
require_login_for_checkout();

$cartobj  = new Cart();
$orderobj = new OrdersClass();
$items    = $cartobj->getitems();

if (empty($items)) {
    header('Location: cart.php');
    exit();
}

$sum   = $cartobj->getsummary($items);
$error = '';
$form  = [
    'name'    => $_SESSION['user_name'] ?? '',
    'phone'   => '',
    'address' => '',
    'city'    => '',
    'zip'     => '',
    'notes'   => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
    foreach ($form as $key => $value) {
        $form[$key] = trim($_POST[$key] ?? '');
    }

    if ($form['name'] === '' || $form['phone'] === '' || $form['address'] === '' || $form['city'] === '') {
        $error = 'Please fill in all required fields';
    } elseif (!preg_match('/^[0-9+\-\s()]{7,20}$/', $form['phone'])) {
        $error = 'Invalid phone number';
    } else {
        try {
            $orderId = $orderobj->create((int) $_SESSION['user_id'], $form, $items, $sum);
            $cartobj->clear();
            header('Location: order-receipt.php?id=' . $orderId);
            exit();
        } catch (RuntimeException $ex) {
            $error = $ex->getMessage();
        } catch (Throwable $ex) {
            error_log($ex->getMessage());
            $error = 'Something went wrong while placing your order. Please try again.';
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
      Checkout | Glow &amp; Co.
    </title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&amp;family=Jost:wght@300;400;500&amp;display=swap" rel="stylesheet">
    <!-- Shared styles (tokens, layout, components) -->
    <link rel="stylesheet" href="css/base.css">
    <!-- Page-specific styles -->
    <link rel="stylesheet" href="css/checkout.css">
  </head>
  <body class="store">
    <?php render_header('cart'); ?>
    <main>
      <section class="page-head">
        <div class="container">
          <h1>
            Checkout
          </h1>
          <p>
            Delivery details and payment method
          </p>
        </div>
      </section>
      <section class="section">
        <div class="container cart-grid">
          <form action="checkout.php" method="post" class="panel" novalidate>
            <?php if ($error !== ''): ?>
            <div class="alert error" role="alert">
              <?= e($error) ?>
            </div>
            <?php endif; ?>
            <h2>
              Delivery details
            </h2>
            <div class="two">
              <div class="field">
                <label for="name">
                  Full name
                </label>
                <input type="text" id="name" name="name" value="<?= e($form['name']) ?>" placeholder="Your name" required>
              </div>
              <div class="field">
                <label for="phone">
                  Phone
                </label>
                <input type="tel" id="phone" name="phone" value="<?= e($form['phone']) ?>" placeholder="+962 ..." required>
              </div>
            </div>
            <div class="field">
              <label for="address">
                Street address
              </label>
              <input type="text" id="address" name="address" value="<?= e($form['address']) ?>" placeholder="Street, building, apartment" required>
            </div>
            <div class="two">
              <div class="field">
                <label for="city">
                  City
                </label>
                <input type="text" id="city" name="city" value="<?= e($form['city']) ?>" placeholder="City" required>
              </div>
              <div class="field">
                <label for="zip">
                  Postal code
                </label>
                <input type="text" id="zip" name="zip" value="<?= e($form['zip']) ?>" placeholder="Optional">
              </div>
            </div>
            <div class="field">
              <label for="notes">
                Order notes
              </label>
              <textarea id="notes" name="notes" rows="3" placeholder="Anything we should know?"><?= e($form['notes']) ?></textarea>
            </div>
            <h2>
              Payment method
            </h2>
            <label class="pay">
              <input type="radio" name="payment" value="cod" checked>
              <span>
                <strong>
                  Cash on Delivery (COD)
                </strong>
                <small>
                  Pay in cash when your order arrives. No card details needed.
                </small>
              </span>
            </label>
            <button type="submit" name="place_order" class="btn btn-block">
              Place order
            </button>
          </form>
          <aside class="panel summary">
            <h2>
              Your order
            </h2>
            <?php foreach ($items as $it): ?>
            <div class="line">
              <span>
                <?= e($it['name']) ?> &times; <?= $it['qty'] ?>
              </span>
              <span>
                <?= money($it['subtotal']) ?>
              </span>
            </div>
            <?php endforeach; ?>
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
          </aside>
        </div>
      </section>
    </main>
    <?php render_footer(); ?>
  </body>
</html>