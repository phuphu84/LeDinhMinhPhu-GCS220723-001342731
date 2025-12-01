<?php
require __DIR__ . '/../login/Check.php';

try {
    include __DIR__ . '/../../includes/DatabaseConnection.php';
    include __DIR__ . '/../../includes/DatabaseFunctions.php';

    $errors = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $moduleName = trim($_POST['module_name'] ?? '');

        if ($moduleName === '') {
            $errors[] = 'Module name is required.';
        }

        if (empty($errors)) {
            insertModule($pdo, $moduleName);
            $basePath = defined('AUTH_BASE_PATH') ? AUTH_BASE_PATH : '';
            header('Location: ' . $basePath . '/admin/modules/index.php');
            exit;
        }
    }

    $title = 'Add module';
    ob_start();
    include __DIR__ . '/../../templates/admin/modules/addmodule.html.php';
    $output = ob_get_clean();
} catch (PDOException $e) {
    $title = 'Error adding module';
    $output = 'Database error: ' . $e->getMessage();
}

include __DIR__ . '/../../templates/admin/layout.html.php';
?>
