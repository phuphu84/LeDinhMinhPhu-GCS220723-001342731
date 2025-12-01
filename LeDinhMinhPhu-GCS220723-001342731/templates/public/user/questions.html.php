<?php
$basePath = defined('AUTH_BASE_PATH') ? AUTH_BASE_PATH : '';
$imageBasePath = $basePath . '/assets/images/';
$imageDir = __DIR__ . '/../../../assets/images/';
?>
<div class="content">
  <?php if ($statusMessage !== ''): ?>
    <p class="notice"><?= htmlspecialchars($statusMessage, ENT_QUOTES, 'UTF-8'); ?></p>
  <?php endif; ?>

  <section class="your-questions">
    <h2>Your questions</h2>
    <?php if (empty($userQuestions)): ?>
      <p class="muted">You have not submitted any questions yet.</p>
    <?php else: ?>
      <table class="question-table">
        <tr>
          <th>Image</th>
          <th>Question Text</th>
          <th>Question Date</th>
          <th>Module Name</th>
          <th>Edit</th>
          <th>Delete</th>
        </tr>
        <?php foreach ($userQuestions as $question): ?>
          <tr>
            <td class="question-image">
              <?php
                $imageFile = $question['image'] ?? '';
                $imageSrc = $imageBasePath . '1.jpg';
                if ($imageFile !== '' && is_file($imageDir . $imageFile)) {
                    $imageSrc = $imageBasePath . $imageFile;
                }
              ?>
              <img src="<?= htmlspecialchars($imageSrc, ENT_QUOTES, 'UTF-8'); ?>" alt="question image">
            </td>
            <td class="question-text">
              <strong>Question Text:</strong>
              <?= htmlspecialchars($question['questiontext'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
            </td>
            <td class="question-date">
              <?= htmlspecialchars($question['questiondate'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
            </td>
            <td class="question-module">
              <?= htmlspecialchars($question['module_name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
            </td>
            <td class="question-edit">
              <a href="<?= htmlspecialchars($basePath, ENT_QUOTES, 'UTF-8'); ?>/public/user/editquestion.php?id=<?= htmlspecialchars((string) ($question['id'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">Edit</a>
            </td>
            <td class="question-delete">
              <form action="<?= htmlspecialchars($basePath, ENT_QUOTES, 'UTF-8'); ?>/public/user/deletequestion.php" method="post">
                <input type="hidden" name="id" value="<?= htmlspecialchars((string) ($question['id'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
                <input type="submit" value="Delete">
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </table>
    <?php endif; ?>
  </section>

  <form class="question-search" method="get" action="<?= htmlspecialchars($basePath, ENT_QUOTES, 'UTF-8'); ?>/public/user/questions.php">
    <label for="search">Search questions:</label>
    <input id="search" name="search" type="text" value="<?= htmlspecialchars($searchTerm, ENT_QUOTES, 'UTF-8'); ?>">
    <button type="submit">Search</button>
    <?php if ($searchTerm !== ''): ?>
      <a class="reset-link" href="<?= htmlspecialchars($basePath, ENT_QUOTES, 'UTF-8'); ?>/public/user/questions.php">Clear filter</a>
    <?php endif; ?>
  </form>

  <p><?= $totalQuestions ?> questions have been submitted to the Internet Question Database.</p>

  <?php if ($searchTerm !== ''): ?>
    <p class="muted">Showing <?= $filteredCount ?> matching question(s) for "<?= htmlspecialchars($searchTerm, ENT_QUOTES, 'UTF-8'); ?>".</p>
  <?php endif; ?>

  <?php if ($filteredCount > 0): ?>
    <table class="question-table">
      <tr>
        <th>Image</th>
        <th>Question Text</th>
        <th>Module</th>
        <th>Question Date</th>
        <th>User</th>
      </tr>

      <?php foreach ($questions as $question): ?>
        <tr>
          <td class="question-image">
            <?php
              $imageFile = $question['image'] ?? '';
              $imageSrc = $imageBasePath . '1.jpg';
              if ($imageFile !== '' && is_file($imageDir . $imageFile)) {
                  $imageSrc = $imageBasePath . $imageFile;
              }
            ?>
            <img src="<?= htmlspecialchars($imageSrc, ENT_QUOTES, 'UTF-8'); ?>" alt="question image">
          </td>

          <td class="question-text">
            <strong>Question Text:</strong>
            <?= htmlspecialchars($question['questiontext'], ENT_QUOTES, 'UTF-8') ?>
          </td>

          <td class="question-module">
            <strong>Module:</strong>
            <?= htmlspecialchars($question['module_name'] ?? '', ENT_QUOTES, 'UTF-8') ?>
          </td>

          <td class="question-date">
            <?= htmlspecialchars($question['questiondate'], ENT_QUOTES, 'UTF-8') ?>
          </td>
          <td class="question-user">
            <?php if (!empty($question['username'])): ?>
              <?= htmlspecialchars($question['username'], ENT_QUOTES, 'UTF-8'); ?><br>
              <span class="muted"><?= htmlspecialchars($question['useremail'], ENT_QUOTES, 'UTF-8'); ?></span>
            <?php else: ?>
              <span class="muted">Unassigned</span>
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
    </table>
  <?php else: ?>
    <p>No questions match your search.</p>
  <?php endif; ?>
</div>
