<?php
require_once 'database/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$user = $_SESSION['user'] ?? null;

if (!$user) {
    header('Location: login.php');
    exit;
}

$userId = $user['id'];

// Обработка изменения количества и удаления товара
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = $_POST['product_id'] ?? null;

    if ($productId) {
        if (isset($_POST['update_item'])) {
            $quantity = (int)$_POST['quantity'];
            if ($quantity < 1) {
                $quantity = 1;
            }

            $sql = "UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$quantity, $userId, $productId]);

        } elseif (isset($_POST['delete_item'])) {
            $sql = "DELETE FROM cart WHERE user_id = ? AND product_id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$userId, $productId]);
        }
    }

    header('Location: cart.php');
    exit;
}

// Получаем содержимое корзины с данными о товарах, включая изображения
$sql = "
    SELECT p.id as product_id, p.title, p.price, p.description, p.image, c.quantity
    FROM cart c
    JOIN product p ON c.product_id = p.id
    WHERE c.user_id = ?
";
$stmt = $pdo->prepare($sql);
$stmt->execute([$userId]);
$cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Корзина</title>
    <link rel="stylesheet" href="assets/css/cart.css">
    <link rel="stylesheet" href="assets/css/main.css" />
</head>
<body>

    <?php include 'header.php'; ?>
    <h1>Ваша корзина</h1>
    <a href="index.php">назад</a>

    <?php if (empty($cartItems)): ?>
        <p>Корзина пуста.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($cartItems as $item): ?>
                <li>
                    <?php if (!empty($item['image'])): ?>
                        <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['title']) ?>">
                    <?php else: ?>
                        <img src="assets/images/no-image.png" alt="Нет изображения">
                    <?php endif; ?>

                    <div class="product-info">
                        <strong><?= htmlspecialchars($item['title']) ?></strong>
                        <p>Цена: <?= $item['price'] ?> ₽</p>

                        <form class="cart-item-form" method="post" action="cart.php">
                            <div class="form-inline">
                                <label for="quantity-<?= $item['product_id'] ?>">Кол-во:</label>
                                <input id="quantity-<?= $item['product_id'] ?>" type="number" name="quantity" value="<?= $item['quantity'] ?>" min="1" />
                                <br><button type="submit" name="update_item">Обновить</button>
                                <button type="submit" name="delete_item">Удалить</button>
                            </div>
                            <input type="hidden" name="product_id" value="<?= $item['product_id'] ?>" />
                        </form>
                        <p>Сумма: <?= $item['price'] * $item['quantity'] ?> ₽</p>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
        <a href="make_order.php">Оформить заказ</a><br>
    <?php endif; ?>
</body>
</html>
