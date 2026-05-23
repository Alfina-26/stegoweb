<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!defined('DB_HOST')) {
    define('DB_HOST', 'sql107.byethost5.com');
    define('DB_USER', 'b5_42001145');
    define('DB_PASS', 'pina1234');
    define('DB_NAME', 'b5_42001145');
    define('UPLOAD_DIR', __DIR__ . '/../assets/uploads/stego/');
    define('MAX_FILE_SIZE', 5 * 1024 * 1024);
    define('ALLOWED_TYPES', ['image/png', 'image/bmp']);
}

function getDB() {
    static $pdo = null;
    if ($pdo === null) {
        try {
            $pdo = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
                DB_USER, DB_PASS,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                 PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
            );
        } catch (PDOException $e) {
            die(json_encode(['error' => 'Koneksi database gagal: ' . $e->getMessage()]));
        }
    }
    return $pdo;
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function redirect($url) {
    header("Location: $url");
    exit;
}