<div class="content">
  <?php if (!empty($errors)): ?>
    <div class="errors">
      <?php foreach ($errors as $error): ?>
        <p><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <form action="" method="post">
    <input type="hidden" name="id" value="<?= htmlspecialchars($module['id'], ENT_QUOTES, 'UTF-8'); ?>">

    <label for="module_name">Module name</label>
    <input type="text" id="module_name" name="module_name" value="<?= htmlspecialchars($_POST['module_name'] ?? $module['module_name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>

    <input type="submit" value="Update Module">
  </form>
</div>
