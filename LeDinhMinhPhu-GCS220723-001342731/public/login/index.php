<?php
require_once __DIR__ . '/../../includes/Auth.php';
require_once __DIR__ . '/../../includes/DatabaseConnection.php';

authStartSession();
$currentUser = currentUser();
$basePath = defined('AUTH_BASE_PATH') ? AUTH_BASE_PATH : '';
if ($currentUser !== null) {
  $redirect = $currentUser['role'] === 'admin'
    ? $basePath . '/admin/dashboard/index.php'
    : $basePath . '/public/index.php';
  header('Location: ' . $redirect);
  exit;
}

$error = isset($_GET['error']);
$loggedOut = isset($_GET['logged_out']);
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Sign In</title>
  <link rel="stylesheet" href="<?= htmlspecialchars($basePath, ENT_QUOTES, 'UTF-8'); ?>/assets/css/questions.css">
</head>
<body>
<header>
  <h1>Internet Question Database - Sign In</h1>
</header>

<main>
  <div class="content" style="max-width:420px;">
    <h2>Please sign in</h2>
    <p class="muted">Sign in with your email address and password.</p>

    <?php if ($loggedOut): ?>
      <p class="muted">You have been signed out.</p>
    <?php endif; ?>

    <?php if ($error): ?>
      <div class="errors">
        <p>Invalid email or password. Please try again.</p>
      </div>
    <?php endif; ?>

    <form action="<?= htmlspecialchars($basePath, ENT_QUOTES, 'UTF-8'); ?>/public/login/process.php" method="post">
      <label for="email">Email</label>
      <input type="email" id="email" name="email" required>

      <label for="password">Password</label>
      <input type="password" id="password" name="password" required>

      <input type="submit" value="Sign In">
    </form>

    <p class="muted" style="margin-top:1rem;">Need an account? <a href="<?= htmlspecialchars($basePath, ENT_QUOTES, 'UTF-8'); ?>/public/login/signup.php">Create one now</a>.</p>
  </div>
</main>
</body>
</html>
