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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['quantities'])) {
    foreach ($_POST['quantities'] as $productId => $quantity) {
        $quantity = (int)$quantity;

        if ($quantity > 0) {
            // Проверим, есть ли уже такой товар в корзине
            $stmt = $pdo->prepare("SELECT id, quantity FROM cart WHERE user_id = ? AND product_id = ?");
            $stmt->execute([$userId, $productId]);
            $existing = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($existing) {
                // Обновляем количество
                $newQuantity = $existing['quantity'] + $quantity;
                $updateStmt = $pdo->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
                $updateStmt->execute([$newQuantity, $existing['id']]);
            } else {
                // Добавляем новую запись
                $insertStmt = $pdo->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
                $insertStmt->execute([$userId, $productId, $quantity]);
            }
        }
    }
}

header('Location: cart.php');
exit;
