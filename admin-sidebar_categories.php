<?php
require_once 'database/db.php';

$sql = "SELECT * FROM category ORDER BY title";
$stmt = $pdo->query($sql);
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Получаем выбранную категорию из GET (если есть)
$selectedCategoryId = $_GET['category_id'] ?? null;
?>

<div class="sidebar-categories">
    <h3>Категории</h3>
    <ul>
        <li>
            <a href="admin-products.php" <?= $selectedCategoryId === null ? 'class="active"' : '' ?>>Все товары</a>
        </li>
        <?php foreach ($categories as $category): ?>
            <li>
                <a href="admin-products.php?category_id=<?= $category['id'] ?>"
                   <?= $selectedCategoryId == $category['id'] ? 'class="active"' : '' ?>>
                    <?= htmlspecialchars($category['title']) ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

<style>
.sidebar-categories {
    margin-top: 43px;
    width: 200px;
    padding: 20px;
    background: #f5f5f5;
    border-radius: 10px;
}

.sidebar-categories h3 {
    margin-top: 0;
    margin-bottom: 10px;
}

.sidebar-categories ul {
    list-style: none;
    padding-left: 0;
}

.sidebar-categories li {
    margin-bottom: 8px;
}

.sidebar-categories a {
    text-decoration: none;
    color: #333;
    font-weight: 500;
}

.sidebar-categories a.active,
.sidebar-categories a:hover {
    color: #007bff;
    font-weight: 700;
}
</style>
