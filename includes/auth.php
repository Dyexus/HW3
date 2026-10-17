<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start([
        'cookie_httponly' => true,
        'cookie_samesite' => 'Lax'
    ]);
}
if (!isset($_SESSION['userId'])) {
    header('Location: /login.php');
    exit;
}
