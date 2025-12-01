<?php
require_once __DIR__ . '/../includes/Auth.php';

requireLogin();
$currentUser = currentUser();

$title = 'Internet Question Database';
ob_start();
include __DIR__ . '/../templates/public/home.html.php';
$output = ob_get_clean();
include __DIR__ . '/../templates/public/layout.html.php';
?>
