<?php
declare(strict_types=1);
session_start();

/*
 * Koneksi database project.
 * Sesuaikan username/password jika konfigurasi MySQL di komputer berbeda.
 */
$host = 'localhost';
$dbname = 'web1_pertemuan09';
$username = 'root';
$password = '';

try {
    $pdo = new PDO(
        "mysql:host={$host};dbname={$dbname};charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (PDOException $e) {
    exit('Koneksi database gagal. Periksa config.php dan pastikan MySQL aktif.');
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

function e(mixed $value): string {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function csrf_check(): void {
    $token = $_POST['csrf_token'] ?? '';
    if (!is_string($token) || !hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
        http_response_code(403);
        exit('Permintaan tidak valid. Muat ulang halaman lalu coba lagi.');
    }
}

function redirect_to(string $url): never {
    header('Location: ' . $url);
    exit;
}
