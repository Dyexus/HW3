<?php

$dbPath = __DIR__ . '/../database/rewards.db';

if (!is_file($dbPath)) {
    http_response_code(500);
    exit('Database not found. Complete the database setup first.');
}

try {
    $pdo = new PDO('sqlite:' . $dbPath);

    $pdo->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );

    $pdo->setAttribute(
        PDO::ATTR_DEFAULT_FETCH_MODE,
        PDO::FETCH_ASSOC
    );

    $pdo->exec('PRAGMA foreign_keys = ON');
} catch (PDOException $e) {
    error_log($e->getMessage());
    http_response_code(500);
    exit('Unable to connect to the database.');
}