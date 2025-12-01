<?php $basePath = defined('AUTH_BASE_PATH') ? AUTH_BASE_PATH : ''; ?>
<div class="content">
  <p><?= count($modules) ?> modules found.</p>

  <p>
    <a href="<?= htmlspecialchars($basePath, ENT_QUOTES, 'UTF-8'); ?>/admin/modules/addmodule.php" class="button-link">Add module</a>
  </p>

  <table class="question-table">
    <tr>
      <th>ID</th>
      <th>Module Name</th>
      <th>Edit</th>
      <th>Delete</th>
    </tr>

    <?php foreach ($modules as $module): ?>
      <tr>
        <td><?= (int) ($module['id'] ?? 0); ?></td>
        <td><?= htmlspecialchars($module['module_name'], ENT_QUOTES, 'UTF-8'); ?></td>
        <td class="question-edit">
          <a href="<?= htmlspecialchars($basePath, ENT_QUOTES, 'UTF-8'); ?>/admin/modules/editmodule.php?id=<?= (int) ($module['id'] ?? 0); ?>">Edit</a>
        </td>
        <td class="question-delete">
          <form action="<?= htmlspecialchars($basePath, ENT_QUOTES, 'UTF-8'); ?>/admin/modules/deletemodule.php" method="post">
            <input type="hidden" name="id" value="<?= (int) ($module['id'] ?? 0); ?>">
            <input type="submit" value="Delete">
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>
</div>
