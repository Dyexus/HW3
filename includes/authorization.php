<?php
require_once __DIR__ . '/auth.php';

function requireRole(string $role): void {
    if (($_SESSION['role'] ?? '') !== $role) {
        http_response_code(403);
        exit('Access denied.');
    }
}

function csrfToken(): string {
    if (!isset($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function checkCsrf(): void {
    $token = $_POST['csrf'] ?? null;
    if (!is_string($token) ||
        !hash_equals(csrfToken(), $token)) {
        http_response_code(403);
        exit('Invalid form token.');
    }
}
