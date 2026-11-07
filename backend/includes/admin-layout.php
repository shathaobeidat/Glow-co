<?php
/**
 * Glow & Co. - shared admin layout (sidebar + main wrapper)
 *
 * Usage in any admin page:
 *   require_once __DIR__ . '/../../backend/includes/admin-layout.php';
 *   require_admin();                       // top of file, before any output
 *   (every POST form: <?= csrf_field() ?> and check verify_csrf() on submit)
 *   ...
 *   render_admin_header('products');       // active: dashboard | products | categories | orders
 *   ...page content (starts with <div class="a-head">)...
 *   render_admin_footer();
 *
 * The page itself writes <!DOCTYPE>, <head> and <body class="admin"> like the user pages do.
 */

require_once __DIR__ . '/layout.php';

/* ---------- Helpers ---------- */
function is_admin(): bool
{
    return is_logged_in() && ($_SESSION['user_role'] ?? '') === 'admin';
}

/**
 * Only admins may open admin pages.
 * Call at the very top of every admin page.
 */
function require_admin(): void
{
    if (!is_logged_in()) {
        header('Location: ' . SITE_URL . '/backend/auth/login.php');
        exit();
    }
    if (!is_admin()) {
        header('Location: ' . SITE_URL . '/frontend/user/index.php');
        exit();
    }
}

/* ---------- CSRF (for every admin POST form) ---------- */
function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
}

function verify_csrf(): bool
{
    $sent = $_POST['csrf_token'] ?? '';
    return is_string($sent) && hash_equals(csrf_token(), $sent);
}

/* ---------- Header (sidebar + opens <main>) ---------- */
function render_admin_header(string $active = ''): void
{
    $cls = function (string $name) use ($active): string {
        return $active === $name ? ' class="active"' : '';
    };
    $admin = SITE_URL . '/frontend/admin';
    $user  = SITE_URL . '/frontend/user';
    $auth  = SITE_URL . '/backend/auth';
    ?>
    <div class="admin-wrap">
      <aside class="sidebar">
        <a href="<?= $admin ?>/admin-dashboard.php" class="logo">
          Glow
          <span>
            &amp;
          </span>
          Co.
          <small>
            Admin
          </small>
        </a>
        <nav>
          <a href="<?= $admin ?>/admin-dashboard.php"<?= $cls('dashboard') ?>>
            Dashboard
          </a>
          <a href="<?= $admin ?>/admin-products.php"<?= $cls('products') ?>>
            Products
          </a>
          <a href="<?= $admin ?>/admin-categories.php"<?= $cls('categories') ?>>
            Categories
          </a>
          <a href="<?= $admin ?>/admin-orders.php"<?= $cls('orders') ?>>
            Orders
          </a>
        </nav>
        <a href="<?= $user ?>/index.php" class="back">
          &larr; View storefront
        </a>
        <a href="<?= $auth ?>/logout.php" class="back">
          Log out
        </a>
      </aside>
      <main class="admin-main">
    <?php
}

/* ---------- Footer (closes <main> and wrapper) ---------- */
function render_admin_footer(): void
{
    ?>
      </main>
    </div>
    <?php
}