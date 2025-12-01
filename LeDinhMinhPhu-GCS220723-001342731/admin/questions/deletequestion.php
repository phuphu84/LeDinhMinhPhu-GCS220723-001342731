<?php
require __DIR__ . '/../login/Check.php';

include __DIR__ . '/../../includes/DatabaseConnection.php';

$sql = 'DELETE FROM question WHERE id = :id';
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id', $_POST['id']);
$stmt->execute();

$basePath = defined('AUTH_BASE_PATH') ? AUTH_BASE_PATH : '';
header('Location: ' . $basePath . '/admin/questions/index.php');
exit;
?>
