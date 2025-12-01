<?php $errors = $errors ?? []; ?>
<?php if (!empty($errors)): ?>
  <div class="errors">
    <?php foreach ($errors as $error): ?>
      <p><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

<form action="" method="post" enctype="multipart/form-data">
  <label for="questiontext">Type your question here:</label><br>
  <textarea id="questiontext" name="questiontext" rows="3" cols="40"><?= htmlspecialchars($_POST['questiontext'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>

  <?php $selectedUserId = $_POST['user_id'] ?? ($defaultUserId ?? ''); ?>
  <select name="user_id">
      <option value="">Assign to user (optional)</option>
      <?php foreach ($users as $user): ?>
        <option value="<?= $user['id']; ?>" <?= ($selectedUserId !== '' && (int)$selectedUserId === (int)$user['id']) ? 'selected' : ''; ?>>
          <?= htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8'); ?> (<?= htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8'); ?>)
        </option>
      <?php endforeach; ?>
  </select>

  <!-- Module select (under author) -->
  <?php $selectedModule = $_POST['module_name'] ?? ''; ?>
  <select name="module_name" id="module_name">
    <option value="">Module Name</option>
    <?php foreach ($modules as $mod): ?>
      <option value="<?= htmlspecialchars($mod, ENT_QUOTES, 'UTF-8'); ?>" <?= ($selectedModule === $mod) ? 'selected' : ''; ?>>
        <?= htmlspecialchars($mod, ENT_QUOTES, 'UTF-8'); ?>
      </option>
    <?php endforeach; ?>
  </select>

  <label for="image">Choose image:</label>
  <input type="file" id="image" name="image" accept="image/*">

  <input type="submit" name='submit' value="Add">
</form>
