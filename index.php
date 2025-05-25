<?php

require_once 'database/db.php'; // путь к твоему файлу подключения
// if ($pdo) {
//     echo "✅ Успешное подключение к базе данных!";
// } else {    
//     echo "❌ Ошибка подключения.";
// }
?>


<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Home</title>
    <link rel="stylesheet" href="assets/css/main.css" />
</head>
<body>
    <?php include 'header.php'; ?>
    <?php include 'products.php'; ?>
</body>
</html>


