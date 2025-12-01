<?php
require __DIR__ . '/../login/Check.php';

try {
    include __DIR__ . '/../../includes/DatabaseConnection.php';
    include __DIR__ . '/../../includes/DatabaseFunctions.php';

    $contacts = allContacts($pdo);
    $title = 'Contact messages';

    ob_start();
    include __DIR__ . '/../../templates/admin/contacts/index.html.php';
    $output = ob_get_clean();
} catch (PDOException $e) {
    $title = 'An error has occurred';
    $output = 'Database error: ' . $e->getMessage();
}

include __DIR__ . '/../../templates/admin/layout.html.php';
?>
