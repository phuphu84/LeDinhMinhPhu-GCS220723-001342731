<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Wrong Password</title>
	<?php $basePath = defined('AUTH_BASE_PATH') ? AUTH_BASE_PATH : ''; ?>
	<meta http-equiv="refresh" content="2; url=<?= htmlspecialchars($basePath, ENT_QUOTES, 'UTF-8'); ?>/public/login/index.php">
</head>
<body>
<p>Wrong password - Please use the new sign-in page.</p>
<p><a href="<?= htmlspecialchars($basePath, ENT_QUOTES, 'UTF-8'); ?>/public/login/index.php">Go to sign in</a></p>
</body>
</html>

