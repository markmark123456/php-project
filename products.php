<?php
require_once 'database/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$user = $_SESSION['user'] ?? null;

$sql = "SELECT * FROM product";
$stmt = $pdo->query($sql);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Товары</title>
    <link rel="stylesheet" href="assets/css/products.css">
</head>
<body>
    <h1>Список товаров</h1>

    <?php if ($user): ?>
        <form action="add_to_cart.php" method="post">
            <div class="product-list">
                <?php foreach ($products as $product): ?>
                    <div class="product">
                        <h2><?= htmlspecialchars($product['title']) ?></h2>
                        <p>Цена: <?= htmlspecialchars($product['price']) ?> ₽</p>
                        <p><?= htmlspecialchars($product['description']) ?></p>

                        <label>
                            Кол-во:
                            <input type="number" name="quantities[<?= $product['id'] ?>]" value="0" min="0">
                        </label>
                    </div>
                <?php endforeach; ?>
            </div>
            <button type="submit">добавить в корзину</button>
        </form>
    <?php else: ?>
        <p><a href="login.php">Войдите</a>, чтобы сделать заказ</p>
    <?php endif; ?>
</body>
</html>
