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
<header id ="admin">
  <h1>Internet Question Database Admin Area 
    Manage questions, modules and users
  </h1>
</header>

<nav>
  <ul>
    <li><a href="<?= htmlspecialchars($basePath, ENT_QUOTES, 'UTF-8'); ?>/admin/questions/index.php">Questions List</a></li>
    <li><a href="<?= htmlspecialchars($basePath, ENT_QUOTES, 'UTF-8'); ?>/admin/questions/addquestion.php">Add a new question</a></li>
    <li><a href="<?= htmlspecialchars($basePath, ENT_QUOTES, 'UTF-8'); ?>/admin/users/index.php">Users</a></li>
    <li><a href="<?= htmlspecialchars($basePath, ENT_QUOTES, 'UTF-8'); ?>/admin/modules/index.php">Modules</a></li>
    <li><a href="<?= htmlspecialchars($basePath, ENT_QUOTES, 'UTF-8'); ?>/admin/contacts/index.php">Contacts</a></li>
    <li><a href="<?= htmlspecialchars($basePath, ENT_QUOTES, 'UTF-8'); ?>/public/index.php">Public Site</a></li>
    <li><a href="<?= htmlspecialchars($basePath, ENT_QUOTES, 'UTF-8'); ?>/public/login/Logout.php">Logout (<?= htmlspecialchars($currentUser['username'] ?? '', ENT_QUOTES, 'UTF-8'); ?>)</a></li>
  </ul>
</nav>

<main>
  <?= $output ?>
</main>
</body>
</html>
