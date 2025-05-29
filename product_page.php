<?php
require_once 'database/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$user = $_SESSION['user'] ?? null;

// Получаем ID товара из параметра GET
$productId = $_GET['id'] ?? null;

if (!$productId) {
    echo "Товар не найден.";
    exit;
}

// Получаем товар и его категорию из базы
$stmt = $pdo->prepare("
    SELECT product.*, category.title AS category_name
    FROM product
    JOIN category ON product.category_id = category.id
    WHERE product.id = ?
");
$stmt->execute([$productId]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    echo "Товар не найден.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($product['title']) ?></title>
    <link rel="stylesheet" href="assets/css/main.css"/>
    <link rel="stylesheet" href="assets/css/products.css">
    <link rel="stylesheet" href="assets/css/product_page.css">
</head>
<body>
    <?php include 'header.php'; ?>
    
    <a href="index.php" class="back-link">← Назад к товарам</a>

    <div class="product-detail">
        <div class="product-image">
            <?php if (!empty($product['image'])): ?>
                <img src="<?= htmlspecialchars($product['image']) ?>" alt="Изображение товара">
            <?php else: ?>
                <img src="assets/images/no-image.png" alt="Нет изображения">
            <?php endif; ?>
        </div>

        <div class="product-info">
            <h1><?= htmlspecialchars($product['title']) ?></h1>
            <p><strong>Категория:</strong> <?= htmlspecialchars($product['category_name']) ?></p>
            <p><strong>Описание:</strong> <?= htmlspecialchars($product['description']) ?></p>
            <p><strong>Цена:</strong> <?= htmlspecialchars($product['price']) ?> сом</p>

            <?php if ($user): ?>
                <form action="add_to_cart.php" method="post">
                    <label>
                        Кол-во:
                        <input type="number" name="quantity" value="1" min="1">
                    </label>
                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                    <br><button type="submit">Добавить в корзину</button>
                </form>
            <?php else: ?>
                <p><a href="login.php">Войдите</a>, чтобы добавить в корзину</p>
            <?php endif; ?>
        </div>
    </div>


</body>
</html>
