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
            <img src="assets/images/5880208.png" alt="no img" width="50">
        </div>

        <div class="site-title">
           <h3>Добро пожаловать в Умный дом</h3>
        </div>

        <div class="search-container">
            <form action="search.php" method="get">
                <input type="search" id="search" name="search" placeholder="Поиск..." required>
                <button type="submit">Поиск</button>
            </form>
        </div>

        <div>
            <?php if (isset($user)): ?>
                <p>Здравствуйте, <?= htmlspecialchars($user['username'] ?? '') ?>!</p>
                <a href="logout.php">Выйти</a>
            <?php else: ?>
                <a href="login.php">Войти</a> | <a href="registration.php">Регистрация</a>
            <?php endif; ?>
        </div>
        <div>
            <?php if (isset($user)): ?>
                <a href="cart.php">🛒 Корзина</a>
            <?php endif; ?>
        </div>
        <div>
            <?php if (isset($user)): ?>
                <a href="orders.php">заказы</a>
            <?php endif; ?>
        </div>

         <nav>
            <ul>
                
            </ul>
        </nav>

    </div>  
</header>
