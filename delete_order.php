<?php
require_once 'database/db.php';
session_start();

$user = $_SESSION['user'] ?? null;

if (!$user) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $orderId = $_POST['order_id'] ?? null;
    $userId = $user['id'];

    if ($orderId) {
        // Проверка: заказ принадлежит текущему пользователю
        $stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
        $stmt->execute([$orderId, $userId]);
        $order = $stmt->fetch();

        if ($order) {
            // Удаляем сначала позиции заказа
            $pdo->prepare("DELETE FROM order_items WHERE order_id = ?")->execute([$orderId]);
            // Потом сам заказ
            $pdo->prepare("DELETE FROM orders WHERE id = ?")->execute([$orderId]);
        }
    }
}

header('Location: orders.php');
exit;
