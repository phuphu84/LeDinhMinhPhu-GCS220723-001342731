<?php
require __DIR__ . '/../login/Check.php';

try {
    include __DIR__ . '/../../includes/DatabaseConnection.php';
    include __DIR__ . '/../../includes/DatabaseFunctions.php';

    $errors = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        $role = $_POST['role'] ?? 'user';

        if ($username === '') {
            $errors[] = 'Username is required.';
        }

        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'A valid email address is required.';
        }

        if ($password === '') {
            $errors[] = 'Password is required.';
        } elseif (strlen($password) < 6) {
            $errors[] = 'Password must be at least 6 characters long.';
        }

        if ($confirmPassword === '' || $password !== $confirmPassword) {
            $errors[] = 'Password confirmation does not match.';
        }

        if (!in_array($role, ['user', 'admin'], true)) {
            $errors[] = 'Invalid role selected.';
        }

        if (empty($errors)) {
            try {
                insertUser($pdo, $username, $email, $password, $role);
                $basePath = defined('AUTH_BASE_PATH') ? AUTH_BASE_PATH : '';
                header('Location: ' . $basePath . '/admin/users/index.php');
                exit;
            } catch (PDOException $e) {
                if ((int)$e->errorInfo[1] === 1062) {
                    $errors[] = 'Username or email already exists.';
                } else {
                    $errors[] = 'Unable to create user. Please try again.';
                }
            }
        }
    }

    $title = 'Add user';
    ob_start();
    include __DIR__ . '/../../templates/admin/users/adduser.html.php';
    $output = ob_get_clean();
} catch (PDOException $e) {
    $title = 'Error adding user';
    $output = 'Database error: ' . $e->getMessage();
}

include __DIR__ . '/../../templates/admin/layout.html.php';
?>
