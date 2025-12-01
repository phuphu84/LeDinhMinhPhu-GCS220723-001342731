<?php
try {
    include '../includes/DatabaseConnection.php';

    $pdo->exec("CREATE TABLE IF NOT EXISTS contacts (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL,
        subject VARCHAR(255) NOT NULL,
        message TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    echo "Contacts table is ready.";
} catch (PDOException $e) {
    echo "Error creating contacts table: " . $e->getMessage();
}
