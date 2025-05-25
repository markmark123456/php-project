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

// Получаем содержимое корзины с данными о товарах, включая изображения
$sql = "
    SELECT p.title, p.price, p.description, p.image, c.quantity
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
    <link rel="stylesheet" href="assets/css/main.css" />
</head>
<body>
    <h1>Ваша корзина</h1>
    <a href="index.php">назад</a>
    
    <?php if (empty($cartItems)): ?>
        <p>Корзина пуста.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($cartItems as $item): ?>
                <li>
                    <?php if (!empty($item['image'])): ?>
                        <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['title']) ?>">
                    <?php else: ?>
                        <img src="assets/images/no-image.png" alt="Нет изображения">
                    <?php endif; ?>

                    <div class="product-info">
                        <strong><?= htmlspecialchars($item['title']) ?></strong>
                        <p>Цена: <?= $item['price'] ?> ₽</p>
                        <p>Кол-во: <?= $item['quantity'] ?></p>
                        <p>Сумма: <?= $item['price'] * $item['quantity'] ?> ₽</p>
                    </div>
                </li>

            <?php endforeach; ?>
        </ul>
        <a href="make_order.php">Оформить заказ</a><br>
    <?php endif; ?>
</body>
</html>
