<?php
require_once __DIR__ . '/../../backend/includes/layout.php';
require_once __DIR__ . '/../../backend/classes/UsersClass.php';

if (!is_logged_in()) {
    header('Location: ' . SITE_URL . '/backend/auth/login.php');
    exit();
}

$userid  = (int) $_SESSION['user_id'];
$userobj = new UsersClass();
$profile = $userobj->getprofile($userid);
$error = '';
$form  = [
    'name'  => $profile['name'],
    'email' => $profile['email'],
];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_profile'])) {
    $form['name']  = trim($_POST['name'] ?? '');
    $form['email'] = trim($_POST['email'] ?? '');

    if ($form['name'] === '' || $form['email'] === '') {
        $error = 'Name and email are required';
    } elseif (!filter_var($form['email'], FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email address';
    } elseif ($userobj->emailexists($form['email'], $userid)) {
        $error = 'This email is already used by another account.';
    } else {
        $userobj->updateprofile($userid, $form['name'], $form['email']);
        $_SESSION['user_name'] = $form['name'];
        $_SESSION['email']     = $form['email'];
        header('Location: profile.php?updated=1');
        exit();
    }
}

$editing =  isset($_GET['edit']);
$updated = isset($_GET['updated']);
$orders  = $userobj->getorders($userid, 10);

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
      My Profile | Glow &amp; Co.
    </title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&amp;family=Jost:wght@300;400;500&amp;display=swap" rel="stylesheet">

    <link rel="stylesheet" href="css/base.css">
    <!-- Page-specific styles -->
    <link rel="stylesheet" href="css/profile.css">
  </head>
  <body class="store">
    <?php render_header('profile'); ?>
    <main>
      <section class="page-head">
        <div class="container">
          <h1>
            My profile
          </h1>
          <p>
            Your details and recent orders
          </p>
        </div>
      </section>
      <section class="section">
        <div class="container profile-grid">
          <div class="panel">
            <div class="avatar">
              <?= e(mb_strtoupper(mb_substr($profile['name'], 0, 1, 'UTF-8'), 'UTF-8')) ?>
            </div>
           <?php if ($updated): ?>
<div class="alert success" role="status">
  Profile updated successfully.
</div>
<?php endif; ?>
<?php if ($error !== ''): ?>
<div class="alert error" role="alert">
  <?= e($error) ?>
</div>
<?php endif; ?>

<?php if ($editing): ?>
<form action="profile.php" method="post" novalidate>
  <div class="field">
    <label for="name">
      Full name
    </label>
    <input type="text" id="name" name="name" value="<?= e($form['name']) ?>" required>
  </div>
  <div class="field">
    <label for="email">
      Email
    </label>
    <input type="email" id="email" name="email" value="<?= e($form['email']) ?>" required>
  </div>
  <div class="panel-actions">
    <button type="submit" name="save_profile" class="btn">
      Save
    </button>
    <a href="profile.php" class="btn btn-outline">
      Cancel
    </a>
  </div>
</form>
<?php else: ?>
<h2>
  <?= e($profile['name']) ?>
</h2>
<p class="muted">
  <?= e($profile['email']) ?>
</p>
<dl>
  <dt>
    Member since
  </dt>
  <dd>
    <?= e(date('F Y', strtotime($profile['created_at']))) ?>
  </dd>
</dl>
<div class="panel-actions">
  <a href="profile.php?edit=1" class="btn btn-outline">
    Edit
  </a>
  <a href="<?= SITE_URL ?>/backend/auth/logout.php" class="btn btn-outline">
    Log out
  </a>
</div>
<?php endif; ?>
          </div>
          <div class="panel">
            <h2>
              Recent orders
            </h2>
        <div class="table-wrap">
              <table>
                <thead>
                  <tr>
                    <th>
                      Order
                    </th>
                    <th>
                      Date
                    </th>
                    <th>
                      Total
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
                    <td colspan="5">
                      You have no orders yet.
                    </td>
                  </tr>
                  <?php else: ?>
                  <?php foreach ($orders as $order): ?>
                  <tr>
                    <td>
                      #<?= (int) $order['id'] ?>
                    </td>
                    <td>
                      <?= e(date('d M Y', strtotime($order['created_at']))) ?>
                    </td>
                    <td>
                      JOD <?= number_format($order['total'], 2) ?>
                    </td>
                    <td>
                      <span class="<?= $statusClass[strtolower($order['status'])] ?? 'badge' ?>">
                        <?= e($order['status']) ?>
                      </span>
                    </td>
                    <td>
                      <a href="order-receipt.php?id=<?= (int) $order['id'] ?>">
                        View
                      </a>
                    </td>
                  </tr>
                  <?php endforeach; ?>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </section>
    </main>
    <?php render_footer(); ?>
  </body>
</html>