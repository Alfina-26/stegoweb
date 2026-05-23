<?php
require_once __DIR__ . '/../includes/config.php';

$action = $_POST['action'] ?? '';

// ── REGISTER ──────────────────────────────────────────────
if ($action === 'register') {
    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';

    // Validasi
    if (empty($username) || empty($email) || empty($password)) {
        $_SESSION['error'] = 'Semua kolom wajib diisi.';
        redirect('../pages/register.php');
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = 'Format email tidak valid.';
        redirect('../pages/register.php');
    }
    if (strlen($password) < 8) {
        $_SESSION['error'] = 'Password minimal 8 karakter.';
        redirect('../pages/register.php');
    }
    if ($password !== $confirm) {
        $_SESSION['error'] = 'Konfirmasi password tidak cocok.';
        redirect('../pages/register.php');
    }

    $db   = getDB();
    $stmt = $db->prepare("SELECT id FROM users WHERE email = ? OR username = ?");
    $stmt->execute([$email, $username]);
    if ($stmt->fetch()) {
        $_SESSION['error'] = 'Email atau username sudah digunakan.';
        redirect('../pages/register.php');
    }

    $hash = password_hash($password, PASSWORD_BCRYPT);
    $stmt = $db->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->execute([$username, $email, $hash]);

    $_SESSION['success'] = 'Akun berhasil dibuat! Silakan login.';
    redirect('../pages/login.php');
}

// ── LOGIN ─────────────────────────────────────────────────
if ($action === 'login') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $_SESSION['error'] = 'Email dan password wajib diisi.';
        redirect('../pages/login.php');
    }

    $db   = getDB();
    $stmt = $db->prepare("SELECT id, username, password FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($password, $user['password'])) {
        $_SESSION['error'] = 'Email atau password salah.';
        redirect('../pages/login.php');
    }

    $_SESSION['user_id']  = $user['id'];
    $_SESSION['username'] = $user['username'];
    redirect('../pages/dashboard.php');
}

// ── LOGOUT ────────────────────────────────────────────────
if ($action === 'logout') {
    session_destroy();
    redirect('../index.php');
}

redirect('../index.php');
?>
