<?php
require_once __DIR__ . '/../../includes/Auth.php';

requireLogin();
$currentUser = currentUser();

$statusMessages = [
    'created' => 'Your question has been created.',
    'updated' => 'Your question has been updated.',
    'deleted' => 'Your question has been deleted.',
    'notfound' => 'The requested question could not be found.'
];
$statusMessage = '';

try {
    include __DIR__ . '/../../includes/DatabaseConnection.php';
    include __DIR__ . '/../../includes/DatabaseFunctions.php';

    $searchTerm = filter_input(INPUT_GET, 'search', FILTER_UNSAFE_RAW);
    $searchTerm = is_string($searchTerm) ? trim($searchTerm) : '';

    $statusKey = filter_input(INPUT_GET, 'status', FILTER_UNSAFE_RAW);
    if (is_string($statusKey) && isset($statusMessages[$statusKey])) {
        $statusMessage = $statusMessages[$statusKey];
    }

    $currentUserId = (int) ($currentUser['id'] ?? 0);
    $userQuestions = userQuestions($pdo, $currentUserId);
    $questions = allQuestion($pdo, $searchTerm);
    $totalQuestions = totalQuestion($pdo);
    $filteredCount = count($questions);

    $title = 'Question list';

    ob_start();
    include __DIR__ . '/../../templates/public/user/questions.html.php';
    $output = ob_get_clean();

} catch (PDOException $e) {
    $title = 'An error has occurred';
    $output = 'Database error: ' . $e->getMessage();
}

include __DIR__ . '/../../templates/public/layout.html.php';
?>
