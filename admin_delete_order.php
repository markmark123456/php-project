<?php
require_once 'database/db.php';
session_start();

$user = $_SESSION['user'] ?? null;

if (!$user || $user['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$orderId = $_POST['order_id'] ?? null;

if (!$orderId) {
    header('Location: admin-orders.php');
    exit;
}

// Удаление товаров из заказа
$pdo->prepare("DELETE FROM order_items WHERE order_id = ?")->execute([$orderId]);

// Удаление самого заказа
$pdo->prepare("DELETE FROM orders WHERE id = ?")->execute([$orderId]);

// Возврат обратно
header("Location: admin-orders.php");
exit;
