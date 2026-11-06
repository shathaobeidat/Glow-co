
<?php
session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../classes/UsersClass.php'; 
require_once __DIR__ . '/../includes/layout.php';
$error = '';
$name = '';
$email = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['subtn'])) {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $rpass = $_POST['rpass'] ?? '';
    $role = 'customer';

    if ($name === '' || $email === '' || $password === '' || $rpass === '') {
        $error = 'All fields are required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email address';
    } elseif (strlen($password) < 8) {
        $error = 'Password must be at least 8 characters';
    } elseif ($password !== $rpass) {
        $error = 'Passwords do not match';
    } else {
        try {
            $reg = new UsersClass();
            $result = $reg->register($name, $email, $password, $role);

            if ($result) {
                $user = $reg->login($email, $password);
                if ($user) {
                    session_regenerate_id(true);
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['name'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['user_role'] = $user['role'];
                    header('Location: ../../frontend/User/index.php');
                    exit();
                }
            }
           
        }
       catch (PDOException $e) {
    if ($e->getCode() === '23000' && ($e->errorInfo[1] ?? 0) == 1062) {
        $error = 'This email is already registered. Try signing in instead.';
    } else {
        error_log($e->getMessage());
        $error = 'Registration failed. Please try again.';
    }
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
      Register | Glow &amp; Co.
    </title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&amp;family=Jost:wght@300;400;500&amp;display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="css/base.css">

    <link rel="stylesheet" href="css/register.css">
  </head>
  <body class="store">
     <?php render_header('register'); ?>
  
    <main>
      <section class="auth">
        <div class="auth-card">
          <h1>
            Create your account
          </h1>
          <p class="muted">
            Join Glow &amp; Co. in less than a minute.
          </p>
          
      <?php if (!empty($error)): ?>
        <div class="alert alert-error" role="alert"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
      <?php endif; ?>
          <form action="register.php" method="post" novalidate>
            <div class="field">
              <label for="name">
                Full name
              </label>
              <input type="text" id="name" name="name" placeholder="Your name" required>
            </div>
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
              <input type="password" id="password" name="password" placeholder="At least 8 characters" required minlength="8">
            </div>
            <div class="field">
              <label for="confirm">
                Confirm password
              </label>
              <input type="password" id="confirm" name="rpass" placeholder="Repeat password" required>
            </div>
            <button type="submit" class="btn btn-block" name="subtn">
              Register
            </button>
          </form>
          <p class="switch">
            Already a member?
            <a href="login.php">
              Login
            </a>
          </p>
        </div>
      </section>
    </main>
        <?php render_footer(); ?>
  </body>
</html>
