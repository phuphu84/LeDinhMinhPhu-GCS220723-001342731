<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Not Authorised</title>
	<?php $basePath = defined('AUTH_BASE_PATH') ? AUTH_BASE_PATH : ''; ?>
	<meta http-equiv="refresh" content="0; url=<?= htmlspecialchars($basePath, ENT_QUOTES, 'UTF-8'); ?>/public/login/forbidden.php">
</head>
<body>
	<p>You are not authorised to view this page.</p>
	<p><a href="<?= htmlspecialchars($basePath, ENT_QUOTES, 'UTF-8'); ?>/public/login/forbidden.php">Go to the access denied page</a></p>
</body>
</html>
