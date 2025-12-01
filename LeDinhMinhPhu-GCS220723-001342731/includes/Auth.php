<?php
declare(strict_types=1);

const AUTH_BASE_PATH = '/COMP1841/cw';

function authStartSession(): void
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
}

function loginUser(array $user): void
{
    authStartSession();
    $_SESSION['user'] = [
        'id' => $user['id'],
        'username' => $user['username'],
        'email' => $user['email'],
        'role' => $user['role']
    ];
}

function logoutUser(): void
{
    authStartSession();
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
    }
    session_destroy();
}

function currentUser(): ?array
{
    authStartSession();
    return $_SESSION['user'] ?? null;
}

function requireLogin(): void
{
    authStartSession();
    if (!isset($_SESSION['user'])) {
        header('Location: ' . AUTH_BASE_PATH . '/public/login/index.php');
        exit;
    }
}

function requireRole(string $role): void
{
    requireLogin();
    $user = $_SESSION['user'];
    if (($user['role'] ?? '') !== $role) {
        header('Location: ' . AUTH_BASE_PATH . '/public/login/forbidden.php');
        exit;
    }
}

function findUserByEmail(PDO $pdo, string $email): ?array
{
    $stmt = $pdo->prepare('SELECT id, username, email, password_hash, role FROM users WHERE email = :email LIMIT 1');
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    return $user === false ? null : $user;
}

function findUserByUsername(PDO $pdo, string $username): ?array
{
    $stmt = $pdo->prepare('SELECT id, username, email, password_hash, role FROM users WHERE username = :username LIMIT 1');
    $stmt->execute([':username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    return $user === false ? null : $user;
}

function authenticateUser(PDO $pdo, string $email, string $password): ?array
{
    $user = findUserByEmail($pdo, $email);
    if ($user === null) {
        return null;
    }

    if (!password_verify($password, $user['password_hash'])) {
        return null;
    }

    return $user;
}

function registerUser(PDO $pdo, string $username, string $email, string $password): array
{
    if (findUserByUsername($pdo, $username) !== null) {
        throw new RuntimeException('Username is already taken.');
    }

    if (findUserByEmail($pdo, $email) !== null) {
        throw new RuntimeException('Email is already registered.');
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare('INSERT INTO users (username, email, password_hash, role) VALUES (:username, :email, :password_hash, :role)');
    $stmt->execute([
        ':username' => $username,
        ':email' => $email,
        ':password_hash' => $hashedPassword,
        ':role' => 'user'
    ]);

    return findUserByEmail($pdo, $email);
}
