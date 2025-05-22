<?php

//localhost:8080/adduser
require_once 'db.php'; // подключение к БД

$username = "newuser2";
$password = password_hash("123456", PASSWORD_DEFAULT); // хешируем пароль

$sql = "INSERT INTO user (username, password) VALUES (:username, :password)";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':username' => $username,
    ':password' => $password
]);

echo "✅ Пользователь успешно добавлен!";
?>
