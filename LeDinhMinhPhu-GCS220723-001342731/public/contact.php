<?php
require_once __DIR__ . '/../includes/Auth.php';

requireLogin();
$currentUser = currentUser();

try {
    include __DIR__ . '/../includes/DatabaseConnection.php';
    include __DIR__ . '/../includes/DatabaseFunctions.php';

    $errors = [];
    $mailError = '';
    $adminEmail = 'admin@example.com'; // TODO: change to real admin email
    $success = false;

    $modules = allModuleNames($pdo);
    $selectedModule = $_POST['module_name'] ?? '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $selectedModule = trim($_POST['module_name'] ?? '');
        $message = trim($_POST['message'] ?? '');

        if ($name === '') {
            $errors[] = 'Please enter your name.';
        }
        if ($email === '') {
            $errors[] = 'Please enter your email.';
        }
        if ($selectedModule === '') {
            $errors[] = 'Please select a module.';
        } elseif (!in_array($selectedModule, $modules, true)) {
            $errors[] = 'Selected module is invalid.';
        }
        if ($message === '') {
            $errors[] = 'Please enter a message.';
        }

        if (empty($errors)) {
          insertContact($pdo, $name, $email, $selectedModule, $message);
            // Try sending email notification to admin
          $mailSubject = 'New contact message (module: ' . $selectedModule . ')';
            $mailBody = "Name: {$name}\nEmail: {$email}\n\nMessage:\n{$message}";
            $headers = "From: {$email}\r\nReply-To: {$email}";
            if (!@mail($adminEmail, $mailSubject, $mailBody, $headers)) {
                $mailError = 'Message saved, but email notification could not be sent.';
            }
            $success = true;
          $selectedModule = '';
        }
    }

    $title = 'Contact';
    ob_start();
    ?>
    <div class="content">
      <h2>Contact Us</h2>

      <?php if ($success): ?>
        <p>Thank you for your message! We will review it soon.</p>
        <?php if ($mailError): ?>
          <p class="muted"><?= htmlspecialchars($mailError, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php endif; ?>
      <?php endif; ?>

      <?php if (!empty($errors)): ?>
        <div class="errors">
          <?php foreach ($errors as $error): ?>
            <p><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <form action="" method="post">
        <label for="name">Name</label>
        <input type="text" id="name" name="name" value="<?= htmlspecialchars($_POST['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>

        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>

        <label for="module_name">Module</label>
        <select id="module_name" name="module_name" required>
          <option value="">Select a module</option>
          <?php foreach ($modules as $module): ?>
            <option value="<?= htmlspecialchars($module, ENT_QUOTES, 'UTF-8'); ?>" <?= ($selectedModule === $module) ? 'selected' : ''; ?>>
              <?= htmlspecialchars($module, ENT_QUOTES, 'UTF-8'); ?>
            </option>
          <?php endforeach; ?>
        </select>

        <label for="message">Message</label>
        <textarea id="message" name="message" rows="5" required><?= htmlspecialchars($_POST['message'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>

        <input type="submit" value="Send">
      </form>
    </div>
    <?php
    $output = ob_get_clean();
} catch (PDOException $e) {
    $title = 'An error has occurred';
    $output = 'Database error: ' . $e->getMessage();
}

include __DIR__ . '/../templates/public/layout.html.php';
