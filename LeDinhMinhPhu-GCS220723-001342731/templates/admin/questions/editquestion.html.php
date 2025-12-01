<div class="content">
  <?php if (!empty($errors)): ?>
    <div class="errors">
      <?php foreach ($errors as $error): ?>
        <p><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <form action="editquestion.php" method="post" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= $question['id']; ?>">
    <input type="hidden" name="existing_image" value="<?= htmlspecialchars($question['image'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">

    <label for="questiontext">Edit your question:</label><br>
    <textarea id="questiontext" name="questiontext" rows="3" cols="40"><?= htmlspecialchars($question['questiontext'], ENT_QUOTES, 'UTF-8'); ?></textarea>

    <label for="user_id">Assign to user (optional)</label>
    <select name="user_id" id="user_id">
      <option value="">-- none --</option>
      <?php foreach ($users as $user): ?>
        <option value="<?= $user['id']; ?>" <?= (!empty($question['user_id']) && (int) $question['user_id'] === (int) $user['id']) ? 'selected' : ''; ?>>
          <?= htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8'); ?> (<?= htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8'); ?>)
        </option>
      <?php endforeach; ?>
    </select>

    <label for="module_name">Module</label>
    <select name="module_name" id="module_name">
      <option value="">(none)</option>
      <?php foreach ($modules as $mod): ?>
        <option value="<?= htmlspecialchars($mod, ENT_QUOTES, 'UTF-8'); ?>" <?= (isset($question['module_name']) && $question['module_name'] == $mod) ? 'selected' : ''; ?>>
          <?= htmlspecialchars($mod, ENT_QUOTES, 'UTF-8'); ?>
        </option>
      <?php endforeach; ?>
    </select>

    <label for="image">Choose image (optional):</label>
    <input type="file" id="image" name="image" accept="image/*">
    <?php if (!empty($question['image'])): ?>
      <p>Current image: <strong><?= htmlspecialchars($question['image'], ENT_QUOTES, 'UTF-8'); ?></strong></p>
    <?php endif; ?>

    <input type="submit" name="submit" value="Save">
  </form>
</div>
