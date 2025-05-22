<?php
session_start();        // Запускаем сессию
session_unset();        // Очищаем все переменные сессии
session_destroy();      // Уничтожаем сессию
header("Location: home.php"); // Перенаправляем на главную страницу
exit;
