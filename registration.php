<?php
require_once 'database/db.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $phone_number = $_POST['phone_number'] ?? '';

    if ($username === '' || $password === '' || $phone_number === '') {
        $message = 'Пожалуйста, заполните все поля.';
    } else {
        // Сохраняем пароль напрямую (небезопасно!)
        $sql = "INSERT INTO users (username, password, phone_number) VALUES (:username, :password, :phone_number)";
        $stmt = $pdo->prepare($sql);

        try {
            $stmt->execute([
                ':username' => $username,
                ':password' => $password,
                ':phone_number' => $phone_number
            ]);
            $message = "✅ Вы успешно успешно зарегистрировались!";
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
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

        <label for="phone_number">Номер телефона:</label>
        <input type="text" name="phone_number" placeholder="Введите номер телефона" required>

        <button type="submit">Зарегистрироваться</button>
    </form>
    <a href="index.php">назад</a>
    </div>
</body>
</html>
