<?php
require __DIR__ . '/../login/Check.php';

include __DIR__ . '/../../includes/DatabaseConnection.php';
include __DIR__ . '/../../includes/DatabaseFunctions.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $role = $_POST['role'] ?? 'user';
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    $id = (int)($_POST['id'] ?? 0);

    if ($username === '') {
        $errors[] = 'Username is required.';
    }

    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'A valid email address is required.';
    }

    if (!in_array($role, ['user', 'admin'], true)) {
        $errors[] = 'Invalid role selected.';
    }

    if ($password !== '' && strlen($password) < 6) {
        $errors[] = 'New password must be at least 6 characters long.';
    }

    if ($password !== '' && $password !== $confirmPassword) {
        $errors[] = 'Password confirmation does not match.';
    }

    if (empty($errors) && $id > 0) {
        try {
            $newPassword = $password === '' ? null : $password;
            updateUser($pdo, $id, $username, $email, $role, $newPassword);
            $basePath = defined('AUTH_BASE_PATH') ? AUTH_BASE_PATH : '';
            header('Location: ' . $basePath . '/admin/users/index.php');
            exit;
        } catch (PDOException $e) {
            if ((int)$e->errorInfo[1] === 1062) {
                $errors[] = 'Username or email already exists.';
            } else {
                $errors[] = 'Unable to update user. Please try again.';
            }
        }
    }
}

$userId = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
$user = $userId ? getUser($pdo, $userId) : null;

if (!$user) {
    $title = 'User not found';
    $output = 'The requested user does not exist.';
    include __DIR__ . '/../../templates/admin/layout.html.php';
    exit;
}

$title = 'Edit user';
ob_start();
include __DIR__ . '/../../templates/admin/users/edituser.html.php';
$output = ob_get_clean();
include __DIR__ . '/../../templates/admin/layout.html.php';
?>
