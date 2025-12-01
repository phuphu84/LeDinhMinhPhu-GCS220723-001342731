<?php
require_once __DIR__ . '/../../includes/Auth.php';
require_once __DIR__ . '/../../includes/DatabaseConnection.php';

authStartSession();
$basePath = defined('AUTH_BASE_PATH') ? AUTH_BASE_PATH : '';

$emailInput = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$email = filter_var($emailInput, FILTER_VALIDATE_EMAIL) ? $emailInput : '';

if ($email === '' || $password === '') {
    header('Location: ' . $basePath . '/public/login/index.php?error=1');
    exit;
}

$user = authenticateUser($pdo, $email, $password);

if ($user === null) {
    header('Location: ' . $basePath . '/public/login/index.php?error=1');
    exit;
}

loginUser($user);

$redirect = $user['role'] === 'admin'
    ? $basePath . '/admin/dashboard/index.php'
    : $basePath . '/public/index.php';
header('Location: ' . $redirect);
exit;
