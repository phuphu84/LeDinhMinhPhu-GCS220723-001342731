<?php
require_once __DIR__ . '/../../includes/Auth.php';

authStartSession();
$basePath = defined('AUTH_BASE_PATH') ? AUTH_BASE_PATH : '';
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Access denied</title>
  <link rel="stylesheet" href="<?= htmlspecialchars($basePath, ENT_QUOTES, 'UTF-8'); ?>/assets/css/questions.css">
</head>
<body>
<header>
  <h1>Access denied</h1>
</header>

<main>
  <div class="content" style="max-width:520px;">
    <p>You do not have permission to view this page.</p>
    <a class="button-link" href="<?= htmlspecialchars($basePath, ENT_QUOTES, 'UTF-8'); ?>/public/index.php">Return to home</a>
  </div>
</main>
</body>
</html>
