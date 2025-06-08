<?php
// header.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$user = $_SESSION['user'] ?? null;
?>

<link rel="stylesheet" href="assets/css/header.css" />
<header>
    <div class="header-container">

        <div class="logo">
            <a href="admin-page.php">
                <img src="assets/images/5880208.png" alt="no img" width="50">
            </a>
        </div>


        <div class="site-title">
           <h3>Админ панель</h3>
        </div>

        <div class="search-container">
            <form action="admin-search.php" method="get">
                <input type="search" id="search" name="search" placeholder="Поиск..." required>
                <button type="submit">Поиск</button>
            </form>
        </div>

        <div>
            <?php if (isset($user)): ?>
                <p>Здравствуйте Админ </p>
                <a href="logout.php">Выйти</a>
            <?php endif; ?>
        </div>
        <div>
            <?php if (isset($user)): ?>
                <a href="admin-orders.php">Заказы</a>
            <?php endif; ?>
        </div>

         <nav>
            <ul>
                
            </ul>
        </nav>

    </div>  
</header>
