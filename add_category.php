<?php
require_once 'database/db.php';
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['username'] !== 'admin') {
    exit('Доступ запрещён');
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');

    if ($title !== '') {
        $stmt = $pdo->prepare("INSERT INTO category (title) VALUES (?)");
        $stmt->execute([$title]);
        $message = 'Категория успешно добавлена!';
    } else {
        $message = 'Название категории не может быть пустым.';
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Добавить категорию</title>
    <link rel="stylesheet" href="assets/css/main.css" />
    <style>
        .form-container {
            max-width: 400px;
            margin: 40px auto;
            padding: 20px;
            border-radius: 10px;
            background: #f4f4f4;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .form-container h2 {
            margin-bottom: 20px;
        }
        .form-container input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
        }
        .form-container button {
            padding: 10px 20px;
        }
        .message {
            color: green;
        }
    </style>
</head>
<body>

<?php include 'admin-header.php'; ?>

<div class="form-container">
    <h2>Добавить новую категорию</h2>
    <?php if ($message): ?>
        <p class="message"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <form method="post">
        <label>Название категории:
            <input type="text" name="title" required>
        </label><br>
        <button type="submit">Добавить</button>
    </form>

    <br><a href="admin-products.php">⬅ Назад к товарам</a>
</div>

</body>
</html>
