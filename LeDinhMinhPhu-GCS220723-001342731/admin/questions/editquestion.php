<?php
require __DIR__ . '/../login/Check.php';

include __DIR__ . '/../../includes/DatabaseConnection.php';
include __DIR__ . '/../../includes/DatabaseFunctions.php';
include __DIR__ . '/../../includes/QuestionImageUpload.php';

$errors = [];
$question = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $questionId = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    $questionText = trim($_POST['questiontext'] ?? '');
    $moduleName = trim($_POST['module_name'] ?? '');
    $userId = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]) ?: null;
    $existingImage = $_POST['existing_image'] ?? '';
    $newImageName = $existingImage;

    if ($questionId === false || $questionId === null) {
        $errors[] = 'Invalid question identifier.';
    }

    if ($questionText === '') {
        $errors[] = 'Question text is required.';
    }

    if (isset($_FILES['image'])) {
        $processedImage = processQuestionImageUpload(
            $_FILES['image'],
            $errors,
            $existingImage !== '' ? $existingImage : null
        );
        if ($processedImage !== null) {
            $newImageName = $processedImage;
        } else {
            $newImageName = null;
        }
    }

    if (empty($errors) && $questionId !== null) {
        $sql = 'UPDATE question SET
                    questiontext = :questiontext,
                    user_id = :user_id,
                    module_name = :module_name,
                    image = :image
                WHERE id = :id';

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':questiontext', $questionText, PDO::PARAM_STR);

        if ($userId === null) {
            $stmt->bindValue(':user_id', null, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        }

        $stmt->bindValue(':module_name', $moduleName, PDO::PARAM_STR);

        if ($newImageName === null || $newImageName === '') {
            $stmt->bindValue(':image', null, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':image', $newImageName, PDO::PARAM_STR);
        }

        $stmt->bindValue(':id', $questionId, PDO::PARAM_INT);
        $stmt->execute();

        $basePath = defined('AUTH_BASE_PATH') ? AUTH_BASE_PATH : '';
        header('Location: ' . $basePath . '/admin/questions/index.php');
        exit;
    }

    // Reload question for redisplay with errors.
    if ($questionId !== null) {
        $stmt = $pdo->prepare('SELECT * FROM question WHERE id = :id');
        $stmt->bindValue(':id', $questionId, PDO::PARAM_INT);
        $stmt->execute();
        $question = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($question) {
            $question['questiontext'] = $questionText;
            $question['module_name'] = $moduleName;
            $question['user_id'] = $userId;
            $question['image'] = $newImageName;
        }
    }
} else {
    $questionId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);

    if ($questionId === false || $questionId === null) {
        $errors[] = 'Invalid question identifier.';
    } else {
        $stmt = $pdo->prepare('SELECT * FROM question WHERE id = :id');
        $stmt->bindValue(':id', $questionId, PDO::PARAM_INT);
        $stmt->execute();
        $question = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($question === false) {
            $errors[] = 'The requested question could not be found.';
        }
    }
}

if ($question === false || $question === null) {
    $title = 'Question not found';
    $output = 'The requested question does not exist.';
    include __DIR__ . '/../../templates/admin/layout.html.php';
    exit;
}

$modules = allModuleNames($pdo);
$users = allUsers($pdo);

$title = 'Edit question';
ob_start();
include __DIR__ . '/../../templates/admin/questions/editquestion.html.php';
$output = ob_get_clean();
include __DIR__ . '/../../templates/admin/layout.html.php';
?>
