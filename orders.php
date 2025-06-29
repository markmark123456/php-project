<?php
require_once 'database/db.php';
session_start();

$user = $_SESSION['user'] ?? null;

if (!$user) {
    header('Location: login.php');
    exit;
}

$user_id = $user['id'];

// Получаем все заказы пользователя
$stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$user_id]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Мои заказы</title>
    <link rel="stylesheet" href="assets/css/orders.css">
    <link rel="stylesheet" href="assets/css/main.css"/>
</head>
<body>

<?php include 'header.php'; ?>

<div class="container">
    <h2>Мои заказы</h2>

    <a href="index.php" class="back-link">Назад</a>

    <?php if (empty($orders)): ?>
        <p>У вас пока нет заказов.</p>
    <?php else: ?>
        <?php foreach ($orders as $order): ?>
            <div class="order">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <h3>Заказ #<?= $order['id'] ?> — <?= $order['created_at'] ?></h3>
                    <form action="delete_order.php" method="post" onsubmit="return confirm('Вы уверены, что хотите удалить этот заказ?');" style="margin: 0;">
                        <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                        <button type="submit" class="delete-button">Удалить</button>
                    </form>
                </div>
                <p><strong>Сумма:</strong> <?= $order['total_price'] ?> ₽</p>

                <?php
                // Получаем товары для текущего заказа
                $stmtItems = $pdo->prepare("
                    SELECT oi.quantity, oi.price, p.title
                    FROM order_items oi
                    JOIN product p ON oi.product_id = p.id
                    WHERE oi.order_id = ?
                ");
                $stmtItems->execute([$order['id']]);
                $items = $stmtItems->fetchAll(PDO::FETCH_ASSOC);
                ?>

                <table>
                    <thead>
                        <tr>
                            <th>Товар</th>
                            <th>Количество</th>
                            <th>Цена за штуку</th>
                            <th>Итого</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item): ?>
                            <tr>
                                <td><?= htmlspecialchars($item['title']) ?></td>
                                <td><?= $item['quantity'] ?></td>
                                <td><?= $item['price'] ?> ₽</td>
                                <td><?= $item['price'] * $item['quantity'] ?> ₽</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

</div>

</body>
</html>
