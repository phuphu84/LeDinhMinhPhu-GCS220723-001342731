<?php
function query($pdo, $sql, $parameters = []) {
    $query = $pdo->prepare($sql);
    $query->execute($parameters);
    return $query;
}

function updateQuestion($pdo, $questionId, $questiontext) {
    $query = 'UPDATE question SET questiontext = :questiontext WHERE id = :id';
    $parameters = [':questiontext' => $questiontext, ':id' => $questionId];
    query($pdo, $query, $parameters);
}

function deleteQuestion($pdo, $id) {
    $query = 'DELETE FROM question WHERE id = :id';
    $parameters = [':id' => $id];
    query($pdo, $query, $parameters);
}

function insertQuestion(PDO $pdo, string $questionText, ?int $userId = null, ?string $moduleName = null, ?string $imageName = null): void
{
    $sql = 'INSERT INTO question SET
                questiontext = :questiontext,
                questiondate = CURDATE(),
                user_id = :user_id,
                module_name = :module_name,
                image = :image';

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':questiontext', $questionText, PDO::PARAM_STR);

    if ($userId === null) {
        $stmt->bindValue(':user_id', null, PDO::PARAM_NULL);
    } else {
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
    }

    $stmt->bindValue(':module_name', $moduleName ?? '', PDO::PARAM_STR);

    if ($imageName === null || $imageName === '') {
        $stmt->bindValue(':image', null, PDO::PARAM_NULL);
    } else {
        $stmt->bindValue(':image', $imageName, PDO::PARAM_STR);
    }

    $stmt->execute();
}

function getQuestion($pdo, $id) {
    $parameters = [':id' => $id];
    $query = query($pdo, 'SELECT * FROM question WHERE id = :id', $parameters);
    return $query->fetch();
}

function totalQuestion(PDO $pdo) {
    $sql = 'SELECT COUNT(*) FROM question';
    return $pdo->query($sql)->fetchColumn();
}

function allQuestion(PDO $pdo, string $searchTerm = ''): array
{
    $sql = 'SELECT 
                question.id,
                question.questiontext,
                question.questiondate,
                question.image,
                question.module_name,
                question.user_id,
                users.username AS username,
                users.email AS useremail
            FROM question
            LEFT JOIN users ON question.user_id = users.id';

    $parameters = [];

    if ($searchTerm !== '') {
        $sql .= ' WHERE question.questiontext LIKE :searchTerm
            OR question.module_name LIKE :searchTerm
            OR users.username LIKE :searchTerm';
        $parameters[':searchTerm'] = '%' . $searchTerm . '%';
    }

    $sql .= ' ORDER BY question.questiondate DESC';

    $stmt = query($pdo, $sql, $parameters);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function userQuestions(PDO $pdo, int $userId, string $searchTerm = ''): array
{
    $sql = 'SELECT id, questiontext, questiondate, module_name, image
            FROM question
            WHERE user_id = :user_id';

    $parameters = [':user_id' => $userId];

    if ($searchTerm !== '') {
        $sql .= ' AND (questiontext LIKE :searchTerm OR module_name LIKE :searchTerm)';
        $parameters[':searchTerm'] = '%' . $searchTerm . '%';
    }

    $sql .= ' ORDER BY questiondate DESC';

    $stmt = query($pdo, $sql, $parameters);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getQuestionForUser(PDO $pdo, int $questionId, int $userId): ?array
{
    $stmt = query(
        $pdo,
        'SELECT id, questiontext, questiondate, module_name, image, user_id
         FROM question WHERE id = :id AND user_id = :user_id LIMIT 1',
        [':id' => $questionId, ':user_id' => $userId]
    );

    $question = $stmt->fetch(PDO::FETCH_ASSOC);
    return $question === false ? null : $question;
}

function insertUserQuestion(PDO $pdo, int $userId, string $questionText, string $moduleName, ?string $imageName): int
{
    insertQuestion($pdo, $questionText, $userId, $moduleName, $imageName);
    return (int) $pdo->lastInsertId();
}

function updateUserQuestion(PDO $pdo, int $questionId, int $userId, string $questionText, string $moduleName, ?string $imageName): bool
{
    $sql = 'UPDATE question SET
                questiontext = :questiontext,
                module_name = :module_name,
                image = :image
            WHERE id = :id AND user_id = :user_id';

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':questiontext', $questionText, PDO::PARAM_STR);
    $stmt->bindValue(':module_name', $moduleName, PDO::PARAM_STR);

    if ($imageName === null || $imageName === '') {
        $stmt->bindValue(':image', null, PDO::PARAM_NULL);
    } else {
        $stmt->bindValue(':image', $imageName, PDO::PARAM_STR);
    }

    $stmt->bindValue(':id', $questionId, PDO::PARAM_INT);
    $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->rowCount() > 0;
}

function deleteUserQuestion(PDO $pdo, int $questionId, int $userId): bool
{
    $stmt = $pdo->prepare('DELETE FROM question WHERE id = :id AND user_id = :user_id');
    $stmt->bindValue(':id', $questionId, PDO::PARAM_INT);
    $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->rowCount() > 0;
}

function allModules(PDO $pdo): array
{
    $stmt = query($pdo, 'SELECT moduleid, modulename FROM module ORDER BY modulename ASC');
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return array_map(static function (array $row): array {
        return [
            'id' => isset($row['moduleid']) ? (int) $row['moduleid'] : 0,
            'module_name' => $row['modulename'] ?? ''
        ];
    }, $rows);
}

function allModuleNames(PDO $pdo): array
{
    $modules = query($pdo, 'SELECT modulename FROM module ORDER BY modulename ASC');
    return $modules->fetchAll(PDO::FETCH_COLUMN);
}

function insertModule(PDO $pdo, string $moduleName): void
{
    query($pdo, 'INSERT INTO module (modulename) VALUES (:module_name)', [':module_name' => $moduleName]);
}

function updateModule(PDO $pdo, int $id, string $moduleName): void
{
    query($pdo, 'UPDATE module SET modulename = :module_name WHERE moduleid = :id LIMIT 1', [
        ':module_name' => $moduleName,
        ':id' => $id
    ]);
}

function deleteModule(PDO $pdo, int $id): void
{
    query($pdo, 'DELETE FROM module WHERE moduleid = :id', [':id' => $id]);
}

function getModule(PDO $pdo, int $id): ?array
{
    $stmt = query($pdo, 'SELECT moduleid, modulename FROM module WHERE moduleid = :id', [':id' => $id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row === false) {
        return null;
    }

    return [
        'id' => isset($row['moduleid']) ? (int) $row['moduleid'] : 0,
        'module_name' => $row['modulename'] ?? ''
    ];
}

function allUsers(PDO $pdo)
{
    $stmt = $pdo->query('SELECT id, username, email, role, created_at FROM users ORDER BY created_at DESC');
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getUser(PDO $pdo, $id)
{
    $parameters = [':id' => $id];
    $stmt = query($pdo, 'SELECT id, username, email, role, created_at FROM users WHERE id = :id', $parameters);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function insertUser(PDO $pdo, string $username, string $email, string $password, string $role = 'user'): void
{
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $sql = 'INSERT INTO users (username, email, password_hash, role) VALUES (:username, :email, :password_hash, :role)';
    $params = [
        ':username' => $username,
        ':email' => $email,
        ':password_hash' => $hashedPassword,
        ':role' => $role
    ];
    query($pdo, $sql, $params);
}

function updateUser(PDO $pdo, int $id, string $username, string $email, string $role, ?string $newPassword = null): void
{
    $params = [
        ':username' => $username,
        ':email' => $email,
        ':role' => $role,
        ':id' => $id
    ];

    if ($newPassword !== null && $newPassword !== '') {
        $params[':password_hash'] = password_hash($newPassword, PASSWORD_DEFAULT);
        $sql = 'UPDATE users SET username = :username, email = :email, role = :role, password_hash = :password_hash WHERE id = :id';
    } else {
        $sql = 'UPDATE users SET username = :username, email = :email, role = :role WHERE id = :id';
    }

    query($pdo, $sql, $params);
}

function deleteUser(PDO $pdo, int $id): void
{
    $sql = 'DELETE FROM users WHERE id = :id';
    $params = [':id' => $id];
    query($pdo, $sql, $params);
}

function insertContact(PDO $pdo, string $name, string $email, string $subject, string $message): void
{
    $sql = 'INSERT INTO contacts (name, email, subject, message) VALUES (:name, :email, :subject, :message)';
    query($pdo, $sql, [
        ':name' => $name,
        ':email' => $email,
        ':subject' => $subject,
        ':message' => $message
    ]);
}

function allContacts(PDO $pdo)
{
    $sql = 'SELECT id, name, email, subject, message, created_at FROM contacts ORDER BY created_at DESC';
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function deleteContact(PDO $pdo, int $id): void
{
    query($pdo, 'DELETE FROM contacts WHERE id = :id', [':id' => $id]);
}
?>
