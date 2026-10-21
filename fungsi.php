<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function e($value): string {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_valid(): bool {
    return isset($_POST['csrf_token'], $_SESSION['csrf_token']) 
        && hash_equals($_SESSION['csrf_token'], (string)$_POST['csrf_token']);
}

function redirect_to(string $url): never {
    header('Location: ' . $url);
    exit;
}

function valid_id($raw): ?int {
    $id = filter_var($raw, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    return $id === false ? null : $id;
}

function valid_kode(string $kode): bool {
    return (bool)preg_match('/^[A-Z0-9-]{3,20}$/', $kode);
}

function valid_url(string $url): bool {
    if (strlen($url) > 255 || !filter_var($url, FILTER_VALIDATE_URL)) return false;
    $scheme = strtolower((string)parse_url($url, PHP_URL_SCHEME));
    return in_array($scheme, ['http', 'https'], true);
}

function header_html(string $title): void {
    echo '<!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>' . e($title) . '</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>';
}

function footer_html(): void {
    echo '</body></html>';
}

function valid_text(string $value, int $min, int $max): bool {
    $len = mb_strlen($value, 'UTF-8');
    return $len >= $min && $len <= $max;
}

