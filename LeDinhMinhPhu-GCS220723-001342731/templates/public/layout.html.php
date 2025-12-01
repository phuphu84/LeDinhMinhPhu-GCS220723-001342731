<?php
$currentUser = $currentUser ?? currentUser();
$basePath = defined('AUTH_BASE_PATH') ? AUTH_BASE_PATH : '';
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title><?= $title ?></title>
  <link rel="stylesheet" href="<?= htmlspecialchars($basePath, ENT_QUOTES, 'UTF-8'); ?>/assets/css/questions.css">
</head>
<body>
<header>
  <h1>Internet Question Database </h1>
</header>

<nav>
  <ul>
    <li><a href="<?= htmlspecialchars($basePath, ENT_QUOTES, 'UTF-8'); ?>/public/index.php">Home</a></li>
    <li><a href="<?= htmlspecialchars($basePath, ENT_QUOTES, 'UTF-8'); ?>/public/user/questions.php">Questions List</a></li>
    <?php if ($currentUser !== null): ?>
      <li><a href="<?= htmlspecialchars($basePath, ENT_QUOTES, 'UTF-8'); ?>/public/user/addquestion.php">Add a new question</a></li>
    <?php endif; ?>
    <li><a href="<?= htmlspecialchars($basePath, ENT_QUOTES, 'UTF-8'); ?>/public/contact.php">Contact</a></li>
    <?php if ($currentUser !== null && ($currentUser['role'] ?? '') === 'admin'): ?>
      <li><a href="<?= htmlspecialchars($basePath, ENT_QUOTES, 'UTF-8'); ?>/admin/dashboard/index.php">Admin Area</a></li>
    <?php endif; ?>
    <?php if ($currentUser !== null): ?>
      <li><a href="<?= htmlspecialchars($basePath, ENT_QUOTES, 'UTF-8'); ?>/public/login/Logout.php">Logout (<?= htmlspecialchars($currentUser['username'] ?? '', ENT_QUOTES, 'UTF-8'); ?>)</a></li>
    <?php else: ?>
      <li><a href="<?= htmlspecialchars($basePath, ENT_QUOTES, 'UTF-8'); ?>/public/login/index.php">Login</a></li>
      <li><a href="<?= htmlspecialchars($basePath, ENT_QUOTES, 'UTF-8'); ?>/public/login/signup.php">Sign up</a></li>
    <?php endif; ?>
  </ul>
</nav>

<main>
  <?= $output ?>
</main>
</body>
</html>
