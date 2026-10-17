<?php
require_once __DIR__ . '/includes/db.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /login.php');
    exit;
}
$name = $_POST['userName'] ?? '';
$password = $_POST['password'] ?? '';
$stmt = $pdo->prepare('SELECT * FROM USER WHERE userName = ?');
$stmt->execute([is_string($name) ? trim($name) : '']);
$user = $stmt->fetch();
if (!$user || !is_string($password) ||
    !password_verify($password, $user['password'])) {
    header('Location: /login.php?error=1', true, 303);
    exit;
}
session_start([
    'cookie_httponly' => true,
    'cookie_samesite' => 'Lax'
]);
session_regenerate_id(true);
$_SESSION = [
    'userId' => $user['userId'],
    'userName' => $user['userName'],
    'role' => $user['role']
];
header('Location: /card-list.php', true, 303);
exit;
