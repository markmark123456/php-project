<?php
require_once 'database/db.php';
session_start();

$user = $_SESSION['user'] ?? null;

if (!$user) {
    header('Location: login.php');
    exit;
}

// Получаем ID пользователя
$stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
$stmt->execute([$user['username']]); // $user — массив, берем username
$userData = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$userData) {
    echo "Ошибка: пользователь не найден.";
    exit;
}

$user_id = $userData['id'];

// Получаем товары из корзины для этого пользователя
$stmt = $pdo->prepare("SELECT c.product_id, c.quantity, p.price FROM cart c JOIN product p ON c.product_id = p.id WHERE c.user_id = ?");
$stmt->execute([$user_id]);
$cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($cartItems)) {
    echo "Ваша корзина пуста.";
    exit;
}

// Создаём заказ с временным total_price = 0
$stmt = $pdo->prepare("INSERT INTO orders (user_id, created_at, total_price) VALUES (?, NOW(), 0)");
$stmt->execute([$user_id]);
$order_id = $pdo->lastInsertId();

$total_price = 0;

foreach ($cartItems as $item) {
    $quantity = (int) $item['quantity'];
    if ($quantity > 0) {
        $price = $item['price'];
        $subtotal = $price * $quantity;
        $total_price += $subtotal;

        // Вставляем в order_items
        $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        $stmt->execute([$order_id, $item['product_id'], $quantity, $price]);
    }
}

// Обновляем total_price в заказе
$stmt = $pdo->prepare("UPDATE orders SET total_price = ? WHERE id = ?");
$stmt->execute([$total_price, $order_id]);

// Очищаем корзину после оформления заказа
$stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = ?");
$stmt->execute([$user_id]);

echo "Заказ успешно оформлен на сумму {$total_price} ₽. <a href='home.php'>Вернуться</a>";
?>
