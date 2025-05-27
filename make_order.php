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

// Получаем ID пользователя
$stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
$stmt->execute([$user['username']]);
$userData = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$userData) {
    header('Location: cart.php?order_error=1');
    exit;
}

$user_id = $userData['id'];

// Получаем товары из корзины
$stmt = $pdo->prepare("SELECT c.product_id, c.quantity, p.price FROM cart c JOIN product p ON c.product_id = p.id WHERE c.user_id = ?");
$stmt->execute([$user_id]);
$cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($cartItems)) {
    header('Location: cart.php?order_empty=1');
    exit;
}

// Создаём заказ
$stmt = $pdo->prepare("INSERT INTO orders (user_id, created_at, total_price) VALUES (?, NOW(), 0)");
$stmt->execute([$user_id]);
$order_id = $pdo->lastInsertId();

$total_price = 0;

foreach ($cartItems as $item) {
    $quantity = (int)$item['quantity'];
    if ($quantity > 0) {
        $price = $item['price'];
        $subtotal = $price * $quantity;
        $total_price += $subtotal;

        $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        $stmt->execute([$order_id, $item['product_id'], $quantity, $price]);
    }
}

// Обновляем итоговую сумму
$stmt = $pdo->prepare("UPDATE orders SET total_price = ? WHERE id = ?");
$stmt->execute([$total_price, $order_id]);

// Очищаем корзину
$stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = ?");
$stmt->execute([$user_id]);

// Редирект обратно в корзину с сообщением
header("Location: cart.php?order_success=1");
exit;
