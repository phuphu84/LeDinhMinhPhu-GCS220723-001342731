<?php $basePath = defined('AUTH_BASE_PATH') ? AUTH_BASE_PATH : ''; ?>
<div class="content">
  <p><?= count($contacts) ?> messages received.</p>

  <table class="question-table">
    <tr>
      <th>ID</th>
      <th>Name</th>
      <th>Email</th>
      <th>Subject</th>
      <th>Message</th>
      <th>Date</th>
      <th>Delete</th>
    </tr>

    <?php foreach ($contacts as $contact): ?>
      <tr>
        <td><?= htmlspecialchars($contact['id'], ENT_QUOTES, 'UTF-8'); ?></td>
        <td><?= htmlspecialchars($contact['name'], ENT_QUOTES, 'UTF-8'); ?></td>
        <td><?= htmlspecialchars($contact['email'], ENT_QUOTES, 'UTF-8'); ?></td>
        <td><?= htmlspecialchars($contact['subject'], ENT_QUOTES, 'UTF-8'); ?></td>
        <td style="text-align:left; max-width:320px;">
          <?= nl2br(htmlspecialchars($contact['message'], ENT_QUOTES, 'UTF-8')); ?>
        </td>
        <td><?= htmlspecialchars($contact['created_at'], ENT_QUOTES, 'UTF-8'); ?></td>
        <td class="question-delete">
          <form action="<?= htmlspecialchars($basePath, ENT_QUOTES, 'UTF-8'); ?>/admin/contacts/deletecontact.php" method="post">
            <input type="hidden" name="id" value="<?= (int) $contact['id']; ?>">
            <input type="submit" value="Delete">
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>
</div>
