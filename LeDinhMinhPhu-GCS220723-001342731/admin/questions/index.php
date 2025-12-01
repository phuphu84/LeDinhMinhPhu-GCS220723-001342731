<?php
require __DIR__ . '/../login/Check.php';

try {
    include __DIR__ . '/../../includes/DatabaseConnection.php';
    include __DIR__ . '/../../includes/DatabaseFunctions.php';

    $searchTerm = filter_input(INPUT_GET, 'search', FILTER_UNSAFE_RAW);
    $searchTerm = is_string($searchTerm) ? trim($searchTerm) : '';

    $questions = allQuestion($pdo, $searchTerm);
    $title = 'Question list';
    $totalQuestions = totalQuestion($pdo);
    $filteredCount = count($questions);

    ob_start();
    include __DIR__ . '/../../templates/admin/questions/index.html.php';
    $output = ob_get_clean();
} catch (PDOException $e) {
    $title = 'An error has occured';
    $output = 'Database error: ' . $e->getMessage();
}

include __DIR__ . '/../../templates/admin/layout.html.php';
?>
