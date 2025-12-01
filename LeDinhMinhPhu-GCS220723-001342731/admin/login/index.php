<?php require 'Check.php'; ?>
<?php $basePath = defined('AUTH_BASE_PATH') ? AUTH_BASE_PATH : ''; ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Protected Page</title>
</head>
<body>
    <p>Welcome to the protected page! You are logged in.</p>
    <p><a href="<?= htmlspecialchars($basePath, ENT_QUOTES, 'UTF-8'); ?>/admin/login/Logout.php">Logout</a></p>
</body>
</html>
