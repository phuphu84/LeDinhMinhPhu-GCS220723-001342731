<?php
$basePath = defined('AUTH_BASE_PATH') ? AUTH_BASE_PATH : '';
header('Location: ' . $basePath . '/public/login/index.php');
exit;
