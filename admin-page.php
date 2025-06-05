<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin-panel</title>
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="assets/css/admin-page.css">
</head>
<body>
    <?php include 'admin-header.php'; ?>

    <div class="admin-menu">
        <h1>Панель администратора</h1>
        <div class="menu-buttons">
            <a href="admin-products.php" class="admin-button">Управление товарами</a>
            <a href="admin-orders.php" class="admin-button">Заказы клиентов</a>
            <a href="clients.php" class="admin-button">База клиентов</a>
        </div>
    </div>
</body>
</html>
