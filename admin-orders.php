<?php
require_once 'database/db.php';
session_start();

$user = $_SESSION['user'] ?? null;

if (!$user || $user['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Получаем все заказы всех пользователей
$stmt = $pdo->prepare("
    SELECT o.*, u.username 
    FROM orders o
    JOIN users u ON o.user_id = u.id
    ORDER BY o.created_at DESC
");
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Заказы клиентов</title>
    <link rel="stylesheet" href="assets/css/orders.css">
    <link rel="stylesheet" href="assets/css/main.css"/>
</head>
<body>

<?php include 'admin-header.php'; ?>

<div class="container">
    <h2>Все заказы клиентов</h2>

    <a href="admin-page.php" class="back-link">Назад</a>

    <?php if (empty($orders)): ?>
        <p>Нет заказов.</p>
    <?php else: ?>
        <?php foreach ($orders as $order): ?>
            <div class="order">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <h3>Заказ #<?= $order['id'] ?> — <?= $order['created_at'] ?></h3>
                    <form action="admin_delete_order.php" method="post" onsubmit="return confirm('Удалить заказ #<?= $order['id'] ?>?');" style="margin: 0;">
                        <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                        <button type="submit" class="delete-button">Удалить</button>
                    </form>
                </div>
                <p><strong>Пользователь:</strong> <?= htmlspecialchars($order['username']) ?></p>
                <p><strong>Сумма:</strong> <?= $order['total_price'] ?> ₽</p>

                <?php
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
