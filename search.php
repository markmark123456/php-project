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
    <link rel="stylesheet" href="assets/css/products.css">
</head>
<body>

    <?php include 'header.php'; ?>

    <h1>Результаты поиска по запросу: <?= htmlspecialchars($search) ?></h1>

    <?php if (empty($products)): ?>
        <p>Ничего не найдено.</p>
    <?php else: ?>
        <div class="product-list">
            <?php foreach ($products as $product): ?>
                <div class="product">
                    <h2><?= htmlspecialchars($product['title']) ?></h2>
                    <p>Цена: <?= htmlspecialchars($product['price']) ?> ₽</p>
                    <p><?= htmlspecialchars($product['description']) ?></p>

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

    <p><a href="home.php">← Назад на главную</a></p>
</body>
</html>
