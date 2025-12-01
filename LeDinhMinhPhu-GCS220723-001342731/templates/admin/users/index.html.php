<?php $basePath = defined('AUTH_BASE_PATH') ? AUTH_BASE_PATH : ''; ?>
<div class="content">
  <p><?= count($users) ?> users found.</p>

  <p>
    <a href="<?= htmlspecialchars($basePath, ENT_QUOTES, 'UTF-8'); ?>/admin/users/adduser.php" class="button-link">Add new user</a>
  </p>

  <table class="question-table">
    <tr>
      <th>ID</th>
      <th>Username</th>
      <th>Email</th>
      <th>Role</th>
      <th>Created</th>
      <th>Edit</th>
      <th>Delete</th>
    </tr>

    <?php foreach ($users as $user): ?>
      <tr>
        <td><?= htmlspecialchars($user['id'], ENT_QUOTES, 'UTF-8'); ?></td>
        <td><?= htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8'); ?></td>
        <td><?= htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8'); ?></td>
        <td><?= htmlspecialchars($user['role'], ENT_QUOTES, 'UTF-8'); ?></td>
        <td><?= htmlspecialchars($user['created_at'], ENT_QUOTES, 'UTF-8'); ?></td>
        <td class="question-edit">
          <a href="<?= htmlspecialchars($basePath, ENT_QUOTES, 'UTF-8'); ?>/admin/users/edituser.php?id=<?= (int) $user['id']; ?>">Edit</a>
        </td>
        <td class="question-delete">
          <form action="<?= htmlspecialchars($basePath, ENT_QUOTES, 'UTF-8'); ?>/admin/users/deleteuser.php" method="post">
            <input type="hidden" name="id" value="<?= (int) $user['id']; ?>">
            <input type="submit" value="Delete">
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>
</div>
