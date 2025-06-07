<?php
require_once 'database/db.php';
session_start();

$user = $_SESSION['user'] ?? null;

if (!$user || $user['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Получаем всех пользователей, кроме админов, включая phone_number
$stmt = $pdo->prepare("SELECT id, username, role, phone_number FROM users WHERE role != 'admin' ORDER BY username ASC");
$stmt->execute();
$clients = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>База клиентов</title>
    <link rel="stylesheet" href="assets/css/orders.css">
    <link rel="stylesheet" href="assets/css/main.css"/>
</head>
<body>

<?php include 'admin-header.php'; ?>

<div class="container">
    <h2>База клиентов</h2>
    <a href="admin-page.php" class="back-link">Назад</a>

    <?php if (empty($clients)): ?>
        <p>Клиенты не найдены.</p>
    <?php else: ?>
        <div class="order">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Имя пользователя</th>
                        <th>Роль</th>
                        <th>Телефон</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($clients as $client): ?>
                        <tr>
                            <td><?= $client['id'] ?></td>
                            <td><?= htmlspecialchars($client['username']) ?></td>
                            <td><?= $client['role'] ?></td>
                            <td><?= htmlspecialchars($client['phone_number'] ?? '-') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
