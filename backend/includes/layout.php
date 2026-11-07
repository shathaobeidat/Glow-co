<?php
/**
 * Glow & Co. - shared layout (header + footer)
 *
 * Usage in any page:
 *   require_once __DIR__ . '/<path-to>/backend/includes/layout.php';
 *   render_header('home');   // active: home | shop | cart | profile | login | register
 *   ...page content...
 *   render_footer();
 *
 * Checkout page (top of file, before any output):
 *   require_login_for_checkout();
 */

require_once __DIR__ . '/../config/config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* ---------- Base URL (works from backend/auth and frontend/user) ---------- */
if (!defined('SITE_URL')) {
    $root = str_replace('\\', '/', (string) realpath(dirname(__DIR__, 2)));
    $doc  = str_replace('\\', '/', (string) realpath($_SERVER['DOCUMENT_ROOT'] ?? ''));
    $base = ($doc !== '' && strpos($root, $doc) === 0) ? substr($root, strlen($doc)) : '';
    define('SITE_URL', rtrim($base, '/'));
}

/* ---------- Helpers ---------- */
function is_logged_in(): bool
{
    return !empty($_SESSION['user_id']);
}

function cart_count(): int
{
    $count = 0;
    foreach (($_SESSION['cart'] ?? []) as $item) {
        $count += is_array($item)
            ? (int) ($item['qty'] ?? $item['quantity'] ?? 1)
            : (int) $item;
    }
    return $count;
}

/**
 * Guests may add to cart, but must log in before checkout.
 * Call at the very top of checkout.php.
 */
function require_login_for_checkout(): void
{
    if (!is_logged_in()) {
        $_SESSION['redirect_after_login'] = SITE_URL . '/frontend/user/checkout.php';
        header('Location: ' . SITE_URL . '/backend/auth/login.php');
        exit();
    }
}

/* ---------- Shared view helpers (used by cart, products, product-detail...) ---------- */
if (!function_exists('money')) {
    function money($amount): string
    {
        return 'JOD ' . number_format((float) $amount, 2);
    }
}

if (!function_exists('image_style')) {
    /**
     * Returns an inline style attribute for a product image, or '' if none.
     * Accepts: full URL (http/https), a path containing "/" (relative to site root),
     * or a bare filename (looked up in /uploads/).
     */
    function image_style($image): string
    {
        $image = trim((string) $image);
        if ($image === '') {
            return '';
        }
        if (preg_match('#^https?://#i', $image)) {
            $url = $image;
        } elseif (strpos($image, '/') !== false) {
            $url = SITE_URL . '/' . ltrim($image, '/');
        } else {
            $url = SITE_URL . '/uploads/' . rawurlencode($image);
        }
        $url = str_replace(["'", '"', '(', ')'], ['%27', '%22', '%28', '%29'], $url);
        return ' style="background-image:url(\'' . e($url) . '\');background-size:cover;background-position:center"';
    }
}

if (!function_exists('render_product_card')) {
    function render_product_card(array $p): void
    {
        $id    = (int) $p['id'];
        $name  = (string) $p['name'];
        $cat   = (string) ($p['category_name'] ?? '');
        $stock = (int) $p['stock'];
        ?>
        <article class="card">
          <a href="product-detail.php?id=<?= $id ?>" class="thumb c<?= (($id - 1) % 8) + 1 ?>" aria-label="<?= e($name) ?>"<?= image_style($p['image'] ?? '') ?>></a>
          <div class="card-body">
            <?php if ($cat !== ''): ?>
            <span class="tag"><?= e($cat) ?></span>
            <?php endif; ?>
            <h3><a href="product-detail.php?id=<?= $id ?>"><?= e($name) ?></a></h3>
            <div class="card-foot">
              <span class="price"><?= money($p['price']) ?></span>
              <?php if ($stock > 0): ?>
              <form action="cart.php" method="post" class="add-form">
                <input type="hidden" name="action" value="add">
                <input type="hidden" name="product_id" value="<?= $id ?>">
                <input type="hidden" name="qty" value="1">
                <button type="submit" class="btn btn-sm">Add to cart</button>
              </form>
              <?php else: ?>
              <span class="badge warn">Out of stock</span>
              <?php endif; ?>
            </div>
          </div>
        </article>
        <?php
    }
}

/* ---------- Header ---------- */
function render_header(string $active = ''): void
{
    $cls = function (string $name) use ($active): string {
        return $active === $name ? ' class="active"' : '';
    };
    $user = SITE_URL . '/frontend/user';
    $auth = SITE_URL . '/backend/auth';
    ?>
    <header class="site-header">
      <div class="container nav">
        <a href="<?= $user ?>/index.php" class="logo">
          Glow
          <span>
            &amp;
          </span>
          Co.
        </a>
        <input type="checkbox" id="nav-toggle" class="nav-toggle">
        <label for="nav-toggle" class="burger" aria-label="Menu">
          <span>
          </span>
        </label>
        <nav class="menu">
          <a href="<?= $user ?>/index.php"<?= $cls('home') ?>>
            Home
          </a>
          <a href="<?= $user ?>/products.php"<?= $cls('shop') ?>>
            Shop
          </a>
          <a href="<?= $user ?>/cart.php"<?= $cls('cart') ?>>
            Cart (<?= cart_count() ?>)
          </a>
          <?php if (is_logged_in()): ?>
          <a href="<?= $user ?>/profile.php"<?= $cls('profile') ?>>
            Profile
          </a>
          <a href="<?= $auth ?>/logout.php" class="btn btn-sm">
            Logout
          </a>
          <?php else: ?>
          <a href="<?= $auth ?>/login.php"<?= $cls('login') ?>>
            Login
          </a>
          <a href="<?= $auth ?>/register.php" class="btn btn-sm">
            Register
          </a>
          <?php endif; ?>
        </nav>
      </div>
    </header>
    <?php
}

/* ---------- Footer ---------- */
function render_footer(): void
{
    $user = SITE_URL . '/frontend/user';
    $auth = SITE_URL . '/backend/auth';
    ?>
    <footer class="site-footer">
      <div class="container foot-grid">
        <div>
          <a href="<?= $user ?>/index.php" class="logo">
            Glow
            <span>
              &amp;
            </span>
            Co.
          </a>
          <p>
            Handmade scented candles, diffusers, decor trays and gift sets for slow, warm homes.
          </p>
        </div>
        <div>
          <h4>
            Shop
          </h4>
          <a href="<?= $user ?>/products.php">
            All products
          </a>
          <a href="<?= $user ?>/cart.php">
            Cart
          </a>
        </div>
        <div>
          <h4>
            Account
          </h4>
          <?php if (is_logged_in()): ?>
          <a href="<?= $user ?>/profile.php">
            My profile
          </a>
          <?php else: ?>
          <a href="<?= $auth ?>/login.php">
            Login
          </a>
          <a href="<?= $auth ?>/register.php">
            Register
          </a>
          <?php endif; ?>
        </div>
      </div>
      <p class="copy">
        &copy; 2026 Glow &amp; Co. All rights reserved.
      </p>
    </footer>
    <?php
}