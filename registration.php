<?php
require_once 'database/db.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $message = 'Пожалуйста, заполните все поля.';
    } else {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT); // Хешируем пароль

        // Вставляем пользователя в БД
        $sql = "INSERT INTO users (username, password) VALUES (:username, :password)";
        $stmt = $pdo->prepare($sql);

        try {
            $stmt->execute([':username' => $username, ':password' => $passwordHash]);
            $message = "✅ Пользователь успешно зарегистрирован!";
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) { // Нарушение уникальности (дубликат username)
                $message = "Пользователь с таким именем уже существует.";
            } else {
                $message = "Ошибка базы данных: " . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Регистрация</title>
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="assets/css/registration.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="registration-page">
    <h2>Регистрация</h2>
    <?php if ($message): ?>
        <p><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>
    <form method="post" action="">
        <label for="username">Имя пользователя:</label>
        <input type="text" name="username" placeholder="Введите логин" required>

        <label for="password">Пароль:</label>
        <input type="password" name="password" placeholder="Введите пароль" required>

        <button type="submit">Зарегистрироваться</button>
    </form>
    <a href="index.php">назад</a>
    </div>
</body>
</html>
