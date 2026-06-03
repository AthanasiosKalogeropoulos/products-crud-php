<?php

$host    = $_ENV['DB_HOST']     ?? 'localhost';
$db      = $_ENV['DB_NAME']     ?? 'products_db';
$user    = $_ENV['DB_USER']     ?? 'ilias';
$pass    = $_ENV['DB_PASSWORD'] ?? '';
$port    = $_ENV['DB_PORT']     ?? '5432';

$dsn = "pgsql:host=$host;port=$port;dbname=$db";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
