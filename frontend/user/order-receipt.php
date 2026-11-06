<?php require_once __DIR__ . '/../../backend/includes/layout.php'; ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
      Order Receipt | Glow &amp; Co.
    </title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&amp;family=Jost:wght@300;400;500&amp;display=swap" rel="stylesheet">
    <!-- Shared styles (tokens, layout, components) -->
    <link rel="stylesheet" href="css/base.css">
    <!-- Page-specific styles -->
    <link rel="stylesheet" href="css/order-receipt.css">
  </head>
  <body class="store">
         <?php render_header('order-receipt'); ?>
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
              Order #1044 &bull; 05 Nov 2026
            </p>
            <div class="status-row">
              <div>
                <small>
                  Payment method
                </small>
                <span class="badge">
                  COD
                </span>
              </div>
              <div>
                <small>
                  Payment status
                </small>
                <span class="badge warn">
                  Unpaid
                </span>
              </div>
              <div>
                <small>
                  Order status
                </small>
                <span class="badge warn">
                  Pending
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
                  <tr>
                    <td>
                      Vanilla &amp; Lavender Candle
                    </td>
                    <td>
                      $24.00
                    </td>
                    <td>
                      2
                    </td>
                    <td>
                      $48.00
                    </td>
                  </tr>
                  <tr>
                    <td>
                      Marble Coasters Set
                    </td>
                    <td>
                      $35.00
                    </td>
                    <td>
                      1
                    </td>
                    <td>
                      $35.00
                    </td>
                  </tr>
                  <tr>
                    <td colspan="3">
                      <strong>
                        Delivery
                      </strong>
                    </td>
                    <td>
                      $3.00
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
                        $86.00
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
              Amal Hassan, 12 Rose Street, Amman &bull; +962 7 9000 0000
            </p>
            <a href="products.html" class="btn">
              Continue shopping
            </a>
          </div>
        </div>
      </section>
    </main>
   <?php render_footer(); ?>
  </body>
</html>
