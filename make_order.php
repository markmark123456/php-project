<?php
require_once 'database/db.php';
session_start();

$user = $_SESSION['user'] ?? null;

if (!$user) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['quantities'])) {
    $quantities = $_POST['quantities'];

    // Получаем ID пользователя
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute([$user]);
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$userData) {
        echo "Ошибка: пользователь не найден.";
        exit;
    }

    $user_id = $userData['id'];

    // Создаём заказ с временным total_price = 0
    $stmt = $pdo->prepare("INSERT INTO orders (user_id, created_at, total_price) VALUES (?, NOW(), 0)");
    $stmt->execute([$user_id]);
    $order_id = $pdo->lastInsertId();

    $total_price = 0;

    foreach ($quantities as $product_id => $quantity) {
        $quantity = (int) $quantity;
        if ($quantity > 0) {
            // Получаем цену товара
            $stmt = $pdo->prepare("SELECT price FROM product WHERE id = ?");
            $stmt->execute([$product_id]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($product) {
                $price = $product['price'];
                $subtotal = $price * $quantity;
                $total_price += $subtotal;

                // Вставляем в order_items
                $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
                $stmt->execute([$order_id, $product_id, $quantity, $price]);
            }
        }
    }

    // Обновляем total_price
    $stmt = $pdo->prepare("UPDATE orders SET total_price = ? WHERE id = ?");
    $stmt->execute([$total_price, $order_id]);

    echo "Заказ успешно оформлен на сумму {$total_price} ₽. <a href='home.php'>Вернуться</a>";
} else {
    echo "Нет выбранных товаров.";
}
