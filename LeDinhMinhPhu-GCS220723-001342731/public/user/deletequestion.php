<?php
declare(strict_types=1);

require_once __DIR__ . '/../../includes/Auth.php';

requireLogin();
$currentUser = currentUser();
$basePath = defined('AUTH_BASE_PATH') ? AUTH_BASE_PATH : '';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . $basePath . '/public/user/questions.php');
    exit;
}

try {
    include __DIR__ . '/../../includes/DatabaseConnection.php';
    include __DIR__ . '/../../includes/DatabaseFunctions.php';

    $questionId = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    if ($questionId === false || $questionId === null) {
        $status = 'notfound';
    } else {
        $deleted = deleteUserQuestion($pdo, $questionId, (int) ($currentUser['id'] ?? 0));
        $status = $deleted ? 'deleted' : 'notfound';
    }

    header('Location: ' . $basePath . '/public/user/questions.php?status=' . $status);
    exit;

} catch (PDOException $e) {
    $title = 'An error has occurred';
    $output = 'Database error: ' . $e->getMessage();
    include __DIR__ . '/../../templates/public/layout.html.php';
}