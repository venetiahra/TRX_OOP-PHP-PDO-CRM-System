<?php
if (session_status() === PHP_SESSION_NONE) session_start();
date_default_timezone_set('Asia/Manila');
function e($value): string { return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8'); }
function redirect(string $path): void { header('Location: ' . $path); exit(); }
function generate_csrf_token(): string { if (empty($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); return $_SESSION['csrf_token']; }
function verify_csrf_token(?string $token): bool { return isset($_SESSION['csrf_token']) && is_string($token) && hash_equals($_SESSION['csrf_token'], $token); }
function set_flash(string $type, string $message): void { $_SESSION['flash'] = ['type' => $type, 'message' => $message]; }
function get_flash(): ?array { if (!isset($_SESSION['flash'])) return null; $flash = $_SESSION['flash']; unset($_SESSION['flash']); return $flash; }
function build_query(array $params): string { return http_build_query(array_filter($params, fn($v) => $v !== null && $v !== '')); }
function profile_image_url(?string $filename): string {
    if (!$filename) return 'https://via.placeholder.com/160x160.png?text=Client';
    $safe = basename($filename);
    return '../uploads/client_profiles/' . rawurlencode($safe);
}
?>