<?php
declare(strict_types=1);

require_once __DIR__ . '/../../includes/Auth.php';

requireLogin();
$currentUser = currentUser();

$errors = [];
$question = null;

try {
    include __DIR__ . '/../../includes/DatabaseConnection.php';
    include __DIR__ . '/../../includes/DatabaseFunctions.php';
    include __DIR__ . '/../../includes/QuestionImageUpload.php';
    $basePath = defined('AUTH_BASE_PATH') ? AUTH_BASE_PATH : '';

    $questionId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $questionId = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    }

    if ($questionId === false || $questionId === null) {
      header('Location: ' . $basePath . '/public/user/questions.php?status=notfound');
        exit;
    }

    $question = getQuestionForUser($pdo, $questionId, (int) ($currentUser['id'] ?? 0));
    if ($question === null) {
      header('Location: ' . $basePath . '/public/user/questions.php?status=notfound');
        exit;
    }

    $modules = allModuleNames($pdo);
    $currentModule = $question['module_name'] ?? '';
    if ($currentModule !== '' && !in_array($currentModule, $modules, true)) {
        $modules[] = $currentModule;
        sort($modules);
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $questionText = trim($_POST['questiontext'] ?? '');
        $moduleName = trim($_POST['module_name'] ?? '');
        $removeImage = isset($_POST['remove_image']);
        $existingImage = $question['image'] ?? null;

        if ($questionText === '') {
            $errors[] = 'Question text is required.';
        }

        if (empty($modules)) {
            $errors[] = 'No modules are currently available. Please contact an administrator.';
        } elseif ($moduleName === '') {
            $errors[] = 'Please choose a module.';
        } elseif (!in_array($moduleName, $modules, true)) {
            $errors[] = 'The selected module is invalid.';
        }

        $fallbackImage = $removeImage ? null : $existingImage;
        $imageName = $fallbackImage;
        if (empty($errors)) {
            $imageName = processQuestionImageUpload($_FILES['image'] ?? null, $errors, $fallbackImage);
        }

        if (empty($errors)) {
            updateUserQuestion(
                $pdo,
                $questionId,
                (int) ($currentUser['id'] ?? 0),
                $questionText,
                $moduleName,
                $imageName
            );

            header('Location: ' . $basePath . '/public/user/questions.php?status=updated');
            exit;
        }

        $question['questiontext'] = $questionText;
        $question['module_name'] = $moduleName;
        $question['image'] = $imageName;
    }

    $title = 'Edit question';

    ob_start();
    ?>
    <div class="content">
      <h2>Edit question</h2>

      <?php if (!empty($errors)): ?>
        <div class="errors">
          <?php foreach ($errors as $error): ?>
            <p><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <form action="<?= htmlspecialchars($basePath, ENT_QUOTES, 'UTF-8'); ?>/public/user/editquestion.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= htmlspecialchars((string) ($question['id'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">

        <label for="questiontext">Edit your question:</label><br>
        <textarea id="questiontext" name="questiontext" rows="3" cols="40" required><?= htmlspecialchars($question['questiontext'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>

        <label for="module_name">Module</label>
        <?php if (empty($modules)): ?>
          <p class="muted">No modules available. Please contact an administrator.</p>
        <?php else: ?>
          <select name="module_name" id="module_name" required>
            <option value="">Module Name</option>
            <?php foreach ($modules as $moduleName): ?>
              <option value="<?= htmlspecialchars($moduleName, ENT_QUOTES, 'UTF-8'); ?>" <?= ($question['module_name'] ?? '') === $moduleName ? 'selected' : ''; ?>>
                <?= htmlspecialchars($moduleName, ENT_QUOTES, 'UTF-8'); ?>
              </option>
            <?php endforeach; ?>
          </select>
        <?php endif; ?>

        <label for="image">Choose image (optional):</label>
        <input type="file" id="image" name="image" accept="image/*">
        <?php if (!empty($question['image'])): ?>
          <p>Current image: <strong><?= htmlspecialchars($question['image'], ENT_QUOTES, 'UTF-8'); ?></strong></p>
        <?php endif; ?>

        <input type="submit" value="Save">
      </form>
    </div>
    <?php
    $output = ob_get_clean();

} catch (PDOException $e) {
    $title = 'An error has occurred';
    $output = 'Database error: ' . $e->getMessage();
}

include __DIR__ . '/../../templates/public/layout.html.php';