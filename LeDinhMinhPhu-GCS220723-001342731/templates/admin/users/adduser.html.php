<div class="content">
  <?php if (!empty($errors)): ?>
    <div class="errors">
      <?php foreach ($errors as $error): ?>
        <p><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <form action="" method="post">
    <label for="username">Username</label>
    <input type="text" id="username" name="username" value="<?= htmlspecialchars($_POST['username'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>

    <label for="email">Email</label>
    <input type="email" id="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>

    <label for="password">Password</label>
    <input type="password" id="password" name="password" required>

    <label for="confirm_password">Confirm password</label>
    <input type="password" id="confirm_password" name="confirm_password" required>

    <label for="role">Role</label>
    <select id="role" name="role" required>
      <?php $selectedRole = $_POST['role'] ?? 'user'; ?>
      <option value="user" <?= $selectedRole === 'user' ? 'selected' : ''; ?>>User</option>
      <option value="admin" <?= $selectedRole === 'admin' ? 'selected' : ''; ?>>Admin</option>
    </select>

    <input type="submit" value="Add User">
  </form>
</div>
