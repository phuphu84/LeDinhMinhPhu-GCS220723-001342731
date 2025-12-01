<?php
require_once __DIR__ . '/../../includes/Auth.php';
require_once __DIR__ . '/../../includes/DatabaseConnection.php';

authStartSession();
$currentUser = currentUser();
$basePath = defined('AUTH_BASE_PATH') ? AUTH_BASE_PATH : '';
if ($currentUser !== null) {
  $redirect = $currentUser['role'] === 'admin'
    ? $basePath . '/admin/dashboard/index.php'
    : $basePath . '/public/index.php';
  header('Location: ' . $redirect);
    exit;
}

$errors = [];
$usernameValue = trim($_POST['username'] ?? '');
$emailValue = trim($_POST['email'] ?? '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $usernameValue;
    $email = filter_var($emailValue, FILTER_VALIDATE_EMAIL) ? $emailValue : '';
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if ($username === '') {
        $errors[] = 'Username is required.';
    }

    if ($email === '') {
        $errors[] = 'A valid email address is required.';
    }

    if ($password === '') {
        $errors[] = 'Password is required.';
    } elseif (strlen($password) < 6) {
        $errors[] = 'Password must be at least 6 characters.';
    }

    if ($password !== $confirmPassword) {
        $errors[] = 'Password confirmation does not match.';
    }

    if (empty($errors)) {
        try {
            $newUser = registerUser($pdo, $username, $email, $password);
            loginUser($newUser);
          header('Location: ' . $basePath . '/public/index.php');
            exit;
        } catch (RuntimeException $e) {
            $errors[] = $e->getMessage();
        } catch (PDOException $e) {
            $errors[] = 'Could not create account. Please try again later.';
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Sign Up</title>
  <link rel="stylesheet" href="<?= htmlspecialchars($basePath, ENT_QUOTES, 'UTF-8'); ?>/assets/css/questions.css">
</head>
<body>
<header>
  <h1>Create Your Account</h1>
</header>

<main>
  <div class="content" style="max-width:420px;">
    <h2>Sign up</h2>
    <p class="muted">Already have an account? <a href="<?= htmlspecialchars($basePath, ENT_QUOTES, 'UTF-8'); ?>/public/login/index.php">Sign in here</a>.</p>

    <?php if (!empty($errors)): ?>
      <div class="errors">
        <?php foreach ($errors as $error): ?>
          <p><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <form action="<?= htmlspecialchars($basePath, ENT_QUOTES, 'UTF-8'); ?>/public/login/signup.php" method="post" novalidate>
      <label for="username">Username</label>
      <input type="text" id="username" name="username" value="<?= htmlspecialchars($usernameValue, ENT_QUOTES, 'UTF-8'); ?>" required>

      <label for="email">Email</label>
      <input type="email" id="email" name="email" value="<?= htmlspecialchars($emailValue, ENT_QUOTES, 'UTF-8'); ?>" required>

      <label for="password">Password</label>
      <input type="password" id="password" name="password" required>

      <label for="confirm_password">Confirm password</label>
      <input type="password" id="confirm_password" name="confirm_password" required>

      <input type="submit" value="Create Account">
    </form>
  </div>
</main>
</body>
</html>
