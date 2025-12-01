<?php
require __DIR__ . '/../login/Check.php';

include __DIR__ . '/../../includes/DatabaseConnection.php';
include __DIR__ . '/../../includes/DatabaseFunctions.php';

$errors = [];
$module = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $moduleName = trim($_POST['module_name'] ?? '');
    $moduleId = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

    if ($moduleName === '') {
        $errors[] = 'Module name is required.';
    }

    if ($moduleId === false || $moduleId === null) {
        $errors[] = 'Invalid module identifier.';
    } else {
        $module = getModule($pdo, $moduleId);
        if (!$module) {
            $errors[] = 'The requested module does not exist.';
        }
    }

    if (empty($errors)) {
        updateModule($pdo, $moduleId, $moduleName);
        $basePath = defined('AUTH_BASE_PATH') ? AUTH_BASE_PATH : '';
        header('Location: ' . $basePath . '/admin/modules/index.php');
        exit;
    }
} else {
    $moduleId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    if ($moduleId === false || $moduleId === null) {
        $errors[] = 'Invalid module identifier.';
    } else {
        $module = getModule($pdo, $moduleId);
    }
}

if (!$module) {
    $title = 'Module not found';
    $output = 'The requested module does not exist.';
    include __DIR__ . '/../../templates/admin/layout.html.php';
    exit;
}

$title = 'Edit module';
ob_start();
include __DIR__ . '/../../templates/admin/modules/editmodule.html.php';
$output = ob_get_clean();
include __DIR__ . '/../../templates/admin/layout.html.php';
?>
