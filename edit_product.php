<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Изменить товар</title>
    <link rel="stylesheet" href="assets/css/main.css" />
</head>
<body>
    
</body>
</html>

<?php include 'admin-header.php'; ?>

<?php
require_once 'database/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user']) || $_SESSION['user']['username'] !== 'admin') {
    exit('Доступ запрещён');
}

$productId = $_GET['id'] ?? null;

if (!$productId) {
    exit('ID товара не указан.');
}

$stmt = $pdo->prepare("SELECT * FROM product WHERE id = ?");
$stmt->execute([$productId]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    exit('Товар не найден.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $price = $_POST['price'] ?? 0;
    $category_id = $_POST['category_id'] ?? 1;

    $update = $pdo->prepare("UPDATE product SET title = ?, description = ?, price = ?, category_id = ? WHERE id = ?");
    $update->execute([$title, $description, $price, $category_id, $productId]);

    header("Location: admin-product_page.php?id=$productId");
    exit;
}
?>

<form method="post">
    <label>Название: <input name="title" value="<?= htmlspecialchars($product['title']) ?>"></label><br>
    <label>Описание: <textarea name="description"><?= htmlspecialchars($product['description']) ?></textarea></label><br>
    <label>Цена: <input name="price" type="number" value="<?= $product['price'] ?>"></label><br>
    <label>Категория: <input name="category_id" type="number" value="<?= $product['category_id'] ?>"></label><br>
    <button type="submit">Сохранить</button>
</form>
