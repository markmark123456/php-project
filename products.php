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
    <?php if (isset($_GET['added'])): ?>
        <p style="color: green;">Товар добавлен в корзину!</p>
    <?php endif; ?>


    <div class="product-list">
        <?php foreach ($products as $product): ?>
            <div class="product">
                <h2><?= htmlspecialchars($product['title']) ?></h2>
                <p>Цена: <?= htmlspecialchars($product['price']) ?> ₽</p>
                <p><?= htmlspecialchars($product['description']) ?></p>

                <form action="add_to_cart.php" method="post">
                    <label>
                        Кол-во:
                        <input type="number" name="quantity" value="1" min="1">
                    </label>
                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                    <button type="submit">Добавить в корзину</button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>

</body>
</html>
