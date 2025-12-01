<?php
require_once __DIR__ . '/../../includes/Auth.php';

logoutUser();
$basePath = defined('AUTH_BASE_PATH') ? AUTH_BASE_PATH : '';
header('Location: ' . $basePath . '/public/login/index.php?logged_out=1');
exit;
?>
