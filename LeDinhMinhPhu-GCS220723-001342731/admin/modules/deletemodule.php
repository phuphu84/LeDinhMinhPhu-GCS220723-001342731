<?php
require __DIR__ . '/../login/Check.php';

include __DIR__ . '/../../includes/DatabaseConnection.php';
include __DIR__ . '/../../includes/DatabaseFunctions.php';

if (isset($_POST['id'])) {
    deleteModule($pdo, (int)$_POST['id']);
}

$basePath = defined('AUTH_BASE_PATH') ? AUTH_BASE_PATH : '';
header('Location: ' . $basePath . '/admin/modules/index.php');
exit;
