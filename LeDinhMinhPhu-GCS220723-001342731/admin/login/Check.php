<?php
require_once __DIR__ . '/../../includes/Auth.php';

requireRole('admin');
$currentUser = currentUser();
?>

