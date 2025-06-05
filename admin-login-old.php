<?php
session_start();

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $message = 'Пожалуйста, заполните все поля.';
    } elseif ($username === 'admin' && $password === 'adminpas') {
        // Успешная авторизация
        $_SESSION['user'] = [
            'username' => 'admin',
            'role' => 'administrator'
        ];
        header('Location: admin-page.php'); // Страница после входа
        exit;
    } else {
        $message = '❌ Неверное имя пользователя или пароль.';
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Вход</title>
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="assets/css/registration.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="registration-page">
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
    </div>
</body>
</html>
