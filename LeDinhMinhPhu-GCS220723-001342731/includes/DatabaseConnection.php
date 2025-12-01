<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=cw;charset=utf8mb4', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Database connection failed: ' . $e->getMessage();
    exit();
}
?>  
