<?php
require_once 'database/db.php';
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['username'] !== 'admin') {
    exit('Доступ запрещён');
}

$productId = $_GET['id'] ?? null;

if (!$productId) {
    exit('ID товара не указан.');
}

$stmt = $pdo->prepare("DELETE FROM product WHERE id = ?");
$stmt->execute([$productId]);

header("Location: admin-product_page.php");
exit;
