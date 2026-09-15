<?php

$host = 'localhost';
$database = 'ventania';
$username = 'seu_usuario';
$password = 'sua_senha';

try {
    $pdo = new PDO(
        "mysql:host={$host};dbname={$database};charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]
    );
} catch (PDOException $e) {
    var_dump($e->getMessage());
    die();
}

return $pdo;
