<?php
require_once 'database/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$user = $_SESSION['user'] ?? null;

$categoryId = $_GET['category_id'] ?? null;

// Подготовим SQL с фильтром по категории (если есть)
if ($categoryId) {
    $sql = "SELECT * FROM product WHERE category_id = :category_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['category_id' => $categoryId]);
} else {
    $sql = "SELECT * FROM product";
    $stmt = $pdo->query($sql);
}

$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="assets/css/products.css">
</head>
<body>

<div class="container main-layout">

    <?php include 'admin-sidebar_categories.php'; ?>

    <div class="product-section">
        <h2 class="main_label">Список товаров</h2>

        <?php if ($user && $user['role'] === 'admin'): ?>
            <div class="admin-buttons" style="margin-bottom: 20px;">
                <a href="add_product.php" class="btn-admin" style="margin-right: 15px;">➕ Добавить товар</a>
                <a href="add_category.php" class="btn-admin">📁 Добавить категорию</a>
            </div>
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
                    <p><?= htmlspecialchars(mb_substr($product['description'], 0, 20)) ?>...</p>
                    <p>Цена: <?= htmlspecialchars($product['price']) ?> сом</p>
                    <a href="admin-product_page.php?id=<?= $product['id'] ?>">Подробнее</a>

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

        <?php if (isset($_GET['added'])): ?>
            <p style="color: green;">Товар добавлен в корзину!</p>
        <?php endif; ?>
    </div>

</div>

</body>
</html>
