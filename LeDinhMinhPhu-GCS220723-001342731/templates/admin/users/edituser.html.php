<div class="content">
  <?php if (!empty($errors)): ?>
    <div class="errors">
      <?php foreach ($errors as $error): ?>
        <p><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <form action="" method="post">
    <input type="hidden" name="id" value="<?= htmlspecialchars($user['id'], ENT_QUOTES, 'UTF-8'); ?>">

    <label for="username">Username</label>
    <input type="text" id="username" name="username" value="<?= htmlspecialchars($_POST['username'] ?? $user['username'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>

    <label for="email">Email</label>
    <input type="email" id="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? $user['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>

    <label for="role">Role</label>
    <?php $selectedRole = $_POST['role'] ?? $user['role'] ?? 'user'; ?>
    <select id="role" name="role" required>
      <option value="user" <?= $selectedRole === 'user' ? 'selected' : ''; ?>>User</option>
      <option value="admin" <?= $selectedRole === 'admin' ? 'selected' : ''; ?>>Admin</option>
    </select>

    <label for="password">New password</label>
    <input type="password" id="password" name="password" placeholder="Leave blank to keep current password">

    <label for="confirm_password">Confirm new password</label>
    <input type="password" id="confirm_password" name="confirm_password" placeholder="Leave blank to keep current password">

    <input type="submit" value="Update User">
  </form>
</div>
