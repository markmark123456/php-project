<?php
require_once 'database/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user']) || $_SESSION['user']['username'] !== 'admin') {
    exit('Доступ запрещён');
}

// Получаем все категории
$categoriesStmt = $pdo->query("SELECT id, title FROM category ORDER BY title");
$categories = $categoriesStmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = floatval($_POST['price'] ?? 0);
    $category_id = intval($_POST['category_id'] ?? 1);

    $imagePath = null;

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $fileTmp = $_FILES['image']['tmp_name'];
        $fileName = basename($_FILES['image']['name']);
        $targetDir = 'assets/images/';
        $targetPath = $targetDir . $fileName;

        if (move_uploaded_file($fileTmp, $targetPath)) {
            $imagePath = $targetPath;
        }
    }

    $stmt = $pdo->prepare("INSERT INTO product (title, description, price, category_id, image) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$title, $description, $price, $category_id, $imagePath]);

    header("Location: admin-products.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Добавить товар</title>
    <link rel="stylesheet" href="assets/css/main.css" />
    <link rel="stylesheet" href="assets/css/edit_product.css" />
</head>
<body>

<?php include 'admin-header.php'; ?>

<a href="admin-products.php" class="back-link">Назад к товарам</a>

<div class="edit-product-container">
    <h2>Добавление нового товара</h2>
    <form method="post" enctype="multipart/form-data">
        <label>Название:
            <input name="title" required>
        </label><br>

        <label>Описание:
            <textarea name="description" required></textarea>
        </label><br>

        <label>Цена:
            <input name="price" type="number" step="0.01" required>
        </label><br>

        <label>Категория:
            <select name="category_id">
                <?php foreach ($categories as $category): ?>
                    <option value="<?= $category['id'] ?>">
                        <?= htmlspecialchars($category['title']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label><br>

        <label>Изображение:
            <input type="file" name="image">
        </label><br>

        <button type="submit">Добавить товар</button>
    </form>
</div>

</body>
</html>
