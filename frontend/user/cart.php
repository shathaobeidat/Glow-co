
<?php require_once __DIR__ . '/../../backend/includes/layout.php'; ?>
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
        <div class="container cart-grid">
          <form action="#" method="post">
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
                  <tr>
                    <td>
                      <div class="item">
                        <span class="thumb sm c1" style="background-image:url('https://unsplash.com/photos/doGw3H_MDNI/download?force=true&w=900');background-size:cover;background-position:center">
                        </span>
                        <span>
                          Vanilla & Lavender Candle
                        </span>
                      </div>
                    </td>
                    <td>
                      $24.00
                    </td>
                    <td>
                      <input type="number" value="2" min="1" class="qty">
                    </td>
                    <td>
                      $48.00
                    </td>
                    <td>
                      <a href="#" class="danger">
                        Remove
                      </a>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <div class="item">
                        <span class="thumb sm c6" style="background-image:url('https://unsplash.com/photos/MWGdWdUoGwY/download?force=true&w=900');background-size:cover;background-position:center">
                        </span>
                        <span>
                          Marble Coasters Set
                        </span>
                      </div>
                    </td>
                    <td>
                      $35.00
                    </td>
                    <td>
                      <input type="number" value="1" min="1" class="qty">
                    </td>
                    <td>
                      $35.00
                    </td>
                    <td>
                      <a href="#" class="danger">
                        Remove
                      </a>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <button type="submit" class="btn btn-outline">
              Update cart
            </button>
          </form>
          <aside class="panel summary">
            <h2>
              Order summary
            </h2>
            <div class="line">
              <span>
                Subtotal
              </span>
              <span>
                $83.00
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
            <a href="checkout.html" class="btn btn-block">
              Proceed to checkout
            </a>
            <a href="products.html" class="link-center">
              Continue shopping
            </a>
          </aside>
        </div>
      </section>
    </main>
      <?php render_footer(); ?>
  </body>
</html>
