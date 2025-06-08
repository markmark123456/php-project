<?php
require_once 'database/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$user = $_SESSION['user'] ?? null;

$search = $_GET['search'] ?? '';

$products = [];
if ($search) {
    $sql = "SELECT * FROM product WHERE title LIKE :search1 OR description LIKE :search2";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'search1' => "%$search%",
        'search2' => "%$search%"
    ]);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Результаты поиска</title>
    <link rel="stylesheet" href="assets/css/main.css" />
    <link rel="stylesheet" href="assets/css/search.css">
</head>
<body>

<?php include 'header.php'; ?>

<p><a href="index.php" class="back-link">Назад на главную</a></p>
<div class="container">
    <h3>Результаты поиска по запросу: <?= htmlspecialchars($search) ?></h3>
</div>

<?php if (empty($products)): ?>
    <p>Ничего не найдено.</p>
<?php else: ?>
    <div class="product-list">
        <?php foreach ($products as $product): ?>
            <div class="product">

                <!-- ✅ Блок с картинкой -->
                <?php if (!empty($product['image'])): ?>
                    <img src="<?= htmlspecialchars($product['image']) ?>" alt="Изображение товара">
                <?php else: ?>
                    <img src="assets/images/no-image.png" alt="Нет изображения">
                <?php endif; ?>

                <h2><?= htmlspecialchars($product['title']) ?></h2>
                <p>Цена: <?= htmlspecialchars($product['price']) ?> ₽</p>
                <p><?= htmlspecialchars($product['description']) ?></p>

                <a href="product_page.php?id=<?= $product['id'] ?>">Подробнее</a>

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
                    <p><a href="login.php">Войдите</a>, чтобы сделать заказ</p>
                <?php endif; ?>

            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>



</body>
</html>
