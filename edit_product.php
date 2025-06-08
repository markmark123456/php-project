<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Изменить товар</title>
    <link rel="stylesheet" href="assets/css/main.css" />
    <link rel="stylesheet" href="assets/css/edit_product.css" />
</head>
<body>
</body>
</html>

<?php include 'admin-header.php'; ?>

<a href="admin-products.php" class="back-link">Назад к товарам</a>

<?php
require_once 'database/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user']) || $_SESSION['user']['username'] !== 'admin') {
    exit('Доступ запрещён');
}

$productId = $_GET['id'] ?? null;

if (!$productId) {
    exit('ID товара не указан.');
}

// Получаем товар
$stmt = $pdo->prepare("SELECT * FROM product WHERE id = ?");
$stmt->execute([$productId]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    exit('Товар не найден.');
}

// Получаем все категории
$categoriesStmt = $pdo->query("SELECT id, title FROM category ORDER BY title");
$categories = $categoriesStmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $price = $_POST['price'] ?? 0;
    $category_id = $_POST['category_id'] ?? 1;

    $imagePath = $product['image']; // текущий путь к изображению

    // Проверяем загрузку нового изображения
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $fileTmp = $_FILES['image']['tmp_name'];
        $fileName = basename($_FILES['image']['name']);
        $targetDir = 'assets/images/';
        $targetPath = $targetDir . $fileName;

        if (move_uploaded_file($fileTmp, $targetPath)) {
            $imagePath = $targetPath;
        }
    }

    $update = $pdo->prepare("UPDATE product SET title = ?, description = ?, price = ?, category_id = ?, image = ? WHERE id = ?");
    $update->execute([$title, $description, $price, $category_id, $imagePath, $productId]);

    header("Location: admin-product_page.php?id=$productId");
    exit;
}
?>

<div class="edit-product-container">
    <h2>Изменение товара</h2>
    <form method="post" enctype="multipart/form-data">
        <label>Название: <input name="title" value="<?= htmlspecialchars($product['title']) ?>"></label><br>
        <label>Описание: <textarea name="description"><?= htmlspecialchars($product['description']) ?></textarea></label><br>
        <label>Цена: <input name="price" type="number" value="<?= $product['price'] ?>"></label><br>
        <label>Категория:
            <select name="category_id">
                <?php foreach ($categories as $category): ?>
                    <option value="<?= $category['id'] ?>" <?= ($category['id'] == $product['category_id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($category['title']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label><br>

        <label>Текущее изображение:</label><br>
        <img src="<?= htmlspecialchars($product['image']) ?>" alt="Изображение товара" style="max-width:150px;"><br>

        <label>Новое изображение: <input type="file" name="image"></label><br>

        <button type="submit">Сохранить</button>
    </form>
</div>
