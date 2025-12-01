<?php
require __DIR__ . '/../login/Check.php';

include __DIR__ . '/../../includes/DatabaseConnection.php';
include __DIR__ . '/../../includes/DatabaseFunctions.php';

if (isset($_POST['id'])) {
    deleteContact($pdo, (int)$_POST['id']);
}

$basePath = defined('AUTH_BASE_PATH') ? AUTH_BASE_PATH : '';
header('Location: ' . $basePath . '/admin/contacts/index.php');
exit;
