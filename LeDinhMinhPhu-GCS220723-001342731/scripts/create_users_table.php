<?php
try {
    include '../includes/DatabaseConnection.php';

    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL,
        password_hash VARCHAR(255) NOT NULL,
        role ENUM('user','admin') NOT NULL DEFAULT 'user',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY uniq_username (username),
        UNIQUE KEY uniq_email (email)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    $passwordColumn = $pdo->query("SHOW COLUMNS FROM users LIKE 'password_hash'")->fetch(PDO::FETCH_ASSOC);
    if (!$passwordColumn) {
        $pdo->exec("ALTER TABLE users ADD COLUMN password_hash VARCHAR(255) NULL AFTER email");
        $temporaryHash = password_hash('ChangeMe123!', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare('UPDATE users SET password_hash = :hash WHERE password_hash IS NULL OR password_hash = ""');
        $stmt->execute([':hash' => $temporaryHash]);
        $pdo->exec("ALTER TABLE users MODIFY password_hash VARCHAR(255) NOT NULL");
        echo "Added password_hash column. Existing accounts now use temporary password: ChangeMe123!\n";
    }

    $roleColumn = $pdo->query("SHOW COLUMNS FROM users LIKE 'role'")->fetch(PDO::FETCH_ASSOC);
    if (!$roleColumn) {
        $pdo->exec("ALTER TABLE users ADD COLUMN role ENUM('user','admin') NOT NULL DEFAULT 'user' AFTER password_hash");
        $pdo->exec("UPDATE users SET role = 'user' WHERE role IS NULL OR role = ''");
        echo "Added role column to users table.\n";
    }

    $columnExists = $pdo->query("SHOW COLUMNS FROM question LIKE 'user_id'")->fetch(PDO::FETCH_ASSOC);
    if (!$columnExists) {
        $pdo->exec("ALTER TABLE question ADD COLUMN user_id INT NULL");
    }

    $fkExists = $pdo->query("
        SELECT CONSTRAINT_NAME 
        FROM information_schema.KEY_COLUMN_USAGE 
        WHERE TABLE_SCHEMA = DATABASE() 
          AND TABLE_NAME = 'question' 
          AND COLUMN_NAME = 'user_id' 
          AND REFERENCED_TABLE_NAME = 'users'
    ")->fetch(PDO::FETCH_ASSOC);

    if (!$fkExists) {
        $pdo->exec("ALTER TABLE question ADD CONSTRAINT fk_question_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL");
    }

    $adminEmail = 'admin@example.com';
    $existingAdmin = $pdo->prepare('SELECT id FROM users WHERE email = :email LIMIT 1');
    $existingAdmin->execute([':email' => $adminEmail]);
    if ($existingAdmin->fetch(PDO::FETCH_ASSOC) === false) {
        $adminPassword = password_hash('Admin123!', PASSWORD_DEFAULT);
        $insertAdmin = $pdo->prepare('INSERT INTO users (username, email, password_hash, role) VALUES (:username, :email, :password_hash, :role)');
        $insertAdmin->execute([
            ':username' => 'Administrator',
            ':email' => $adminEmail,
            ':password_hash' => $adminPassword,
            ':role' => 'admin'
        ]);
        echo "Created default admin account (email: {$adminEmail}, password: Admin123!).\n";
    }

    echo "Users table and question.user_id column are ready.\n";
} catch (PDOException $e) {
    echo "Error creating users table: " . $e->getMessage();
}
