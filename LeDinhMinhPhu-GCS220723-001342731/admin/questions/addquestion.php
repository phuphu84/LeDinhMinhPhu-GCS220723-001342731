<?php
require __DIR__ . '/../login/Check.php';

try {
    include __DIR__ . '/../../includes/DatabaseConnection.php';
    include __DIR__ . '/../../includes/DatabaseFunctions.php';
    include __DIR__ . '/../../includes/QuestionImageUpload.php';

    $errors = [];

    if (isset($_POST['questiontext'])) {
        $imageName = null;
        $questionText = trim($_POST['questiontext'] ?? '');
        $moduleName = trim($_POST['module_name'] ?? '');
        $userId = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]) ?: null;

        // Handle image upload
        if (isset($_FILES['image'])) {
            $imageName = processQuestionImageUpload($_FILES['image'], $errors, null);
        }

        if (!empty($errors)) {
            $modules = allModuleNames($pdo);
            $users = allUsers($pdo);
            $title = 'Add a new question';
            ob_start();
            include __DIR__ . '/../../templates/admin/questions/addquestion.html.php';
            $output = ob_get_clean();
            include __DIR__ . '/../../templates/admin/layout.html.php';
            exit;
        }

        $sql = 'INSERT INTO question SET 
               questiontext = :questiontext,
            questiondate = CURDATE(),
            user_id = :user_id,
            module_name = :module_name,
            image = :image';

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':questiontext', $questionText, PDO::PARAM_STR);

        if ($userId === null) {
            $stmt->bindValue(':user_id', null, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        }

        $stmt->bindValue(':module_name', $moduleName, PDO::PARAM_STR);

        if ($imageName === null || $imageName === '') {
            $stmt->bindValue(':image', null, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':image', $imageName, PDO::PARAM_STR);
        }
        $stmt->execute();

        $basePath = defined('AUTH_BASE_PATH') ? AUTH_BASE_PATH : '';
        header('Location: ' . $basePath . '/admin/questions/index.php');
        exit;
    }

    $title = 'Add a new question';
    // fetch existing module names (distinct) to populate dropdown
    $modules = allModuleNames($pdo);
    $users = allUsers($pdo);
    ob_start();
    include __DIR__ . '/../../templates/admin/questions/addquestion.html.php';
    $output = ob_get_clean();

} catch (PDOException $e) {
    $title = 'Error adding question';
    $output = 'Database error: ' . $e->getMessage();
}

include __DIR__ . '/../../templates/admin/layout.html.php';
?>
