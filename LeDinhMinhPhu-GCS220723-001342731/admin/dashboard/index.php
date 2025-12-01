<?php
require __DIR__ . '/../login/Check.php';

$title = 'Internet Question Database';
ob_start();
include __DIR__ . '/../../templates/admin/home.html.php';
$output = ob_get_clean();
include __DIR__ . '/../../templates/admin/layout.html.php';
?>
