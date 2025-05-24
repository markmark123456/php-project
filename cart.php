<?php
require_once 'database/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$user = $_SESSION['user'] ?? null;

if (!$user) {
    header('Location: login.php');
    exit;
}

$userId = $user['id'];

// Получаем содержимое корзины с данными о товарах
$sql = "
    SELECT p.title, p.price, p.description, c.quantity
    FROM cart c
    JOIN product p ON c.product_id = p.id
    WHERE c.user_id = ?
";
$stmt = $pdo->prepare($sql);
$stmt->execute([$userId]);
$cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Корзина</title>
    <link rel="stylesheet" href="assets/css/cart.css">
</head>
<body>
    <h1>Ваша корзина</h1>

    <?php if (empty($cartItems)): ?>
        <p>Корзина пуста.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($cartItems as $item): ?>
                <li>
                    <strong><?= htmlspecialchars($item['title']) ?></strong><br>
                    Цена: <?= $item['price'] ?> ₽<br>
                    Кол-во: <?= $item['quantity'] ?><br>
                    Сумма: <?= $item['price'] * $item['quantity'] ?> ₽<br><br>
                </li>
            <?php endforeach; ?>
        </ul>
        <a href="make_order.php">Оформить заказ</a><br>
    <?php endif; ?>
    <a href="home.php">назад</a>
</body>
</html>
