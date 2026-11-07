<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../classes/UsersClass.php'; 
require_once __DIR__ . '/../includes/layout.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['subtn'])) {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        $error = 'All fields are required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email address';
    } else {
        $userObj = new UsersClass();
        $user = $userObj->login($email, $password);
        if ($user) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            session_regenerate_id(true);
            $_SESSION['user_id']   = $user['id'];
            $_SESSION['email']     = $user['email'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['role'];

            if ($user['role'] === 'customer') {
                header("Location: ../../frontend/User/index.php");
                exit();
            } elseif ($user['role'] === 'admin') {
                header("Location: ../../frontend/admin/admin-dashboard.php");
                exit();
            }

          
        } else {
            $error = 'Invalid email or password';
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
      Login | Glow &amp; Co.
    </title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&amp;family=Jost:wght@300;400;500&amp;display=swap" rel="stylesheet">
   
    <link rel="stylesheet" href="css/base.css">
 
    <link rel="stylesheet" href="css/login.css">
  </head>
  <body class="store">
  <?php render_header('login') ?>
    <main>
      <section class="auth">
        <div class="auth-card">
          <h1>
            Welcome back
          </h1>
          <p class="muted">
            Log in to view your orders and checkout faster.
          </p>
          <?php if (!empty($error)): ?>
        <div class="alert alert-error" role="alert"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
      <?php endif; ?>
          <form action="login.php" method="post" novalidate>
            <div class="field">
              <label for="email">
                Email
              </label>
              <input type="email" id="email" name="email" placeholder="you@example.com" required>
            </div>
            <div class="field">
              <label for="password">
                Password
              </label>
              <input type="password" id="password" name="password" placeholder="Your password" required>
            </div>
            <button type="submit" class="btn btn-block" name="subtn">
              Login
            </button>
          </form>
          <p class="switch">
            New here?
            <a href="register.php">
              Create an account
            </a>
          </p>
        </div>
      </section>
    </main>
        <?php render_footer(); ?>

  </body>
</html>
