<?php
try {
    include '../includes/DatabaseConnection.php';

    $sql = "CREATE TABLE IF NOT EXISTS module (
        moduleid INT AUTO_INCREMENT PRIMARY KEY,
        modulename VARCHAR(255) NOT NULL
    )";

    $pdo->exec($sql);
    echo "Module table created successfully.";
} catch (PDOException $e) {
    echo "Error creating module table: " . $e->getMessage();
}
?>
