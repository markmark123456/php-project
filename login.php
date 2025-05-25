<?php
require_once 'database/db.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $message = 'Пожалуйста, заполните все поля.';
    } else {
        // Получаем пользователя из базы данных
        $sql = "SELECT * FROM users WHERE username = :username";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // Успешная авторизация
            session_start();
            $_SESSION['user'] = $user;  // сохраняем весь массив с данными пользователя
            header('Location: index.php'); // Страница после входа
            exit;
        } else {
            $message = '❌ Неверное имя пользователя или пароль.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Вход</title>
    <link rel="stylesheet" href="assets/css/registration.css">
</head>
<body>
    <h2>Вход</h2>
    <?php if ($message): ?>
        <p><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>
    <form method="post" action="">
        <label>Имя пользователя: </label> 
        <input type="text" name="username" placeholder="Введите логин" required>
        <label>Пароль: </label>
        <input type="password" name="password" placeholder="Введите пароль" required>
        <button type="submit">Войти</button>
    </form>
    <a href="index.php">назад</a>
</body>
</html>
