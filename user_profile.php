<?php
require_once 'database/db.php';

session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$user = $_SESSION['user'];

// Обновление данных
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $phone = $_POST['phone_number'] ?? '';

    // Обновляем без хеширования
    $stmt = $pdo->prepare("UPDATE users SET username = ?, password = ?, phone_number = ? WHERE id = ?");
    $stmt->execute([$username, $password, $phone, $user['id']]);

    // Обновляем сессию, чтобы изменения сразу были видны
    $_SESSION['user']['username'] = $username;
    $_SESSION['user']['password'] = $password;
    $_SESSION['user']['phone_number'] = $phone;

    // Обновим локальный массив $user для вывода в форму
    $user['username'] = $username;
    $user['password'] = $password;
    $user['phone_number'] = $phone;

    echo '<p class="success-message">Данные обновлены</p>';
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Личный кабинет</title>
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="assets/css/user_profile.css">
</head>
<body>

<?php include 'header.php'; ?>
<a href="index.php" class="back-link">Назад</a>

<div class="user-profile-container">
    <h2>Личный кабинет</h2>

    <form method="post" class="profile-form">
        <label>Имя пользователя:
            <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>
        </label>

        <label>Пароль:
            <input type="text" name="password" value="<?= htmlspecialchars($user['password']) ?>">
        </label>

        <label>Телефон:
            <input type="tel" name="phone_number" value="<?= htmlspecialchars($user['phone_number'] ?? '') ?>">
        </label>

        <button type="submit">Сохранить изменения</button>
    </form>

    <div class="profile-links">
        <a href="orders.php">Мои заказы</a>
        <a href="cart.php">Корзина</a>
    </div>
</div>

</body>
</html>
