<?php
declare(strict_types=1);

require_once __DIR__ . '/../../includes/Auth.php';

requireLogin();
$currentUser = currentUser();
$basePath = defined('AUTH_BASE_PATH') ? AUTH_BASE_PATH : '';

$errors = [];
$formData = [
    'questiontext' => '',
    'module_name' => '',
    'image' => null
];

try {
    include __DIR__ . '/../../includes/DatabaseConnection.php';
    include __DIR__ . '/../../includes/DatabaseFunctions.php';
    include __DIR__ . '/../../includes/QuestionImageUpload.php';

    $modules = allModuleNames($pdo);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $formData['questiontext'] = trim($_POST['questiontext'] ?? '');
        $formData['module_name'] = trim($_POST['module_name'] ?? '');

        if ($formData['questiontext'] === '') {
            $errors[] = 'Question text is required.';
        }

        if (empty($modules)) {
            $errors[] = 'No modules are currently available. Please contact an administrator.';
        } elseif ($formData['module_name'] === '') {
            $errors[] = 'Please choose a module.';
        } elseif (!in_array($formData['module_name'], $modules, true)) {
            $errors[] = 'The selected module is invalid.';
        }

        $imageName = null;
        if (empty($errors)) {
            $imageName = processQuestionImageUpload($_FILES['image'] ?? null, $errors, null);
        }

        if (empty($errors)) {
            insertUserQuestion(
                $pdo,
                (int) ($currentUser['id'] ?? 0),
                $formData['questiontext'],
                $formData['module_name'],
                $imageName
            );

            header('Location: ' . $basePath . '/public/user/questions.php?status=created');
            exit;
        }
    }

    $title = 'Add a new question';

    ob_start();
    ?>
    <div class="content">
      <h2>Add a new question</h2>

      <?php if (!empty($errors)): ?>
        <div class="errors">
          <?php foreach ($errors as $error): ?>
            <p><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <form action="<?= htmlspecialchars($basePath, ENT_QUOTES, 'UTF-8'); ?>/public/user/addquestion.php" method="post" enctype="multipart/form-data">
        <label for="questiontext">Type your question here:</label><br>
        <textarea id="questiontext" name="questiontext" rows="3" cols="40" required><?= htmlspecialchars($formData['questiontext'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>

        <label for="module_name">Module Name</label>
        <?php if (empty($modules)): ?>
          <p class="muted">No modules available. Please contact an administrator.</p>
        <?php else: ?>
          <select name="module_name" id="module_name" required>
            <option value="">Module Name</option>
            <?php foreach ($modules as $moduleName): ?>
              <option value="<?= htmlspecialchars($moduleName, ENT_QUOTES, 'UTF-8'); ?>" <?= ($formData['module_name'] === $moduleName) ? 'selected' : ''; ?>>
                <?= htmlspecialchars($moduleName, ENT_QUOTES, 'UTF-8'); ?>
              </option>
            <?php endforeach; ?>
          </select>
        <?php endif; ?>

        <label for="image">Choose image:</label>
        <input type="file" id="image" name="image" accept="image/*">

        <input type="submit" value="Create question" <?= empty($modules) ? 'disabled' : ''; ?>>
      </form>
    </div>
    <?php
    $output = ob_get_clean();

} catch (PDOException $e) {
    $title = 'An error has occurred';
    $output = 'Database error: ' . $e->getMessage();
}

include __DIR__ . '/../../templates/public/layout.html.php';