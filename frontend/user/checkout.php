
<?php require_once __DIR__ . '/../../backend/includes/layout.php'; ?>

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
      <?php render_header('checkout'); ?>
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
          <form action="order-receipt.html" method="post" class="panel">
            <h2>
              Delivery details
            </h2>
            <div class="two">
              <div class="field">
                <label for="name">
                  Full name
                </label>
                <input type="text" id="name" name="name" placeholder="Your name" required>
              </div>
              <div class="field">
                <label for="phone">
                  Phone
                </label>
                <input type="tel" id="phone" name="phone" placeholder="+962 ..." required>
              </div>
            </div>
            <div class="field">
              <label for="address">
                Street address
              </label>
              <input type="text" id="address" name="address" placeholder="Street, building, apartment" required>
            </div>
            <div class="two">
              <div class="field">
                <label for="city">
                  City
                </label>
                <input type="text" id="city" name="city" placeholder="City" required>
              </div>
              <div class="field">
                <label for="zip">
                  Postal code
                </label>
                <input type="text" id="zip" name="zip" placeholder="Optional" >
              </div>
            </div>
            <div class="field">
              <label for="notes">
                Order notes
              </label>
              <textarea id="notes" name="notes" rows="3" placeholder="Anything we should know?">
              </textarea>
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
            <button type="submit" class="btn btn-block">
              Place order
            </button>
          </form>
          <aside class="panel summary">
            <h2>
              Your order
            </h2>
            <div class="line">
              <span>
                Vanilla &amp; Lavender Candle &times; 2
              </span>
              <span>
                $48.00
              </span>
            </div>
            <div class="line">
              <span>
                Marble Coasters Set &times; 1
              </span>
              <span>
                $35.00
              </span>
            </div>
            <div class="line">
              <span>
                Delivery
              </span>
              <span>
                $3.00
              </span>
            </div>
            <div class="line total">
              <span>
                Total
              </span>
              <span>
                $86.00
              </span>
            </div>
          </aside>
        </div>
      </section>
    </main>
     <?php render_footer(); ?>
  </body>
</html>
