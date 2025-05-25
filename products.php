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
                
                <?php if (!empty($product['image'])): ?>
                    <img src="<?= htmlspecialchars($product['image']) ?>" alt="Изображение товара">
                <?php else: ?>
                    <img src="assets/images/no-image.png" alt="Нет изображения">
                <?php endif; ?>

                <h2><?= htmlspecialchars($product['title']) ?></h2>
                <p><?= htmlspecialchars($product['description']) ?></p>
                <p>Цена: <?= htmlspecialchars($product['price']) ?>сом</p>
                
                

                <?php if ($user): ?>
                    <form action="add_to_cart.php" method="post">
                        <label>
                            Кол-во:
                            <input type="number" name="quantity" value="1" min="1">
                        </label>
                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                        <button type="submit">Добавить в корзину</button>
                    </form>
                <?php else: ?>
                    <p><a href="login.php">Войдите</a>, чтобы добавить в корзину</p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
