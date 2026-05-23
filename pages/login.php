<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Masuk — StegoWeb</title>
<link href="https://fonts.googleapis.com/css2?family=Space+Mono:wght@400;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
:root { --bg:#fafafa;--surface:#ffffff;--border:#f0e0e8;--accent:#e8637f;--danger:#e05555;--text:#2a2a2a;--muted:#888888;--mono:'Space Mono',monospace;--sans:'DM Sans',sans-serif; }
* { box-sizing:border-box;margin:0;padding:0; }
body { background:#fafafa;color:var(--text);font-family:var(--sans);min-height:100vh;display:flex;flex-direction:column;align-items:center;justify-content:center;padding:1.5rem; }
.box { width:100%;max-width:400px; }
.logo { font-family:var(--mono);font-size:1.1rem;color:var(--accent);text-align:center;margin-bottom:0.5rem; }
.logo span { color:var(--muted); }
h2 { text-align:center;font-size:1.4rem;font-weight:600;margin-bottom:0.4rem; }
.sub { text-align:center;color:var(--muted);font-size:0.85rem;margin-bottom:2rem; }
.card { background:var(--surface);border:1px solid var(--border);border-radius:16px;padding:2rem; }
label { display:block;font-size:0.8rem;color:var(--muted);margin-bottom:0.4rem;margin-top:1rem; }
label:first-of-type { margin-top:0; }
input { width:100%;background:var(--bg);border:1px solid var(--border);border-radius:8px;padding:0.7rem 0.9rem;color:var(--text);font-family:var(--sans);font-size:0.875rem;outline:none;transition:border-color 0.2s; }
input:focus { border-color:var(--accent); }
input::placeholder { color:var(--muted); }
.btn { width:100%;padding:0.75rem;background:#e8637f;color:#fff;border:none;border-radius:8px;font-family:var(--sans);font-size:0.9rem;font-weight:600;cursor:pointer;margin-top:1.5rem;transition:filter 0.2s; }
.btn:hover { filter:brightness(1.1); }
.alert { padding:0.65rem 0.875rem;border-radius:8px;font-size:0.825rem;margin-bottom:1rem; }
.alert-error   { background:#fff0f0;border:1px solid #f5c8c8;color:#e05555; }
.alert-success { background:#f0fff8;border:1px solid #9ee8c0;color:#1d7a50; }
.links { text-align:center;margin-top:1.25rem;font-size:0.83rem;color:var(--muted); }
.links a { color:var(--accent);text-decoration:none;font-weight:500; }
.back { display:block;text-align:center;margin-bottom:1.5rem;color:var(--muted);font-size:0.83rem;text-decoration:none; }
.back:hover { color:var(--text); }
</style>
</head>
<body>
<?php require_once '../includes/config.php'; if (isLoggedIn()) redirect('dashboard.php'); ?>

<div class="box">
  <a href="../index.php" class="back">← Kembali ke beranda</a>
  <div class="logo">STEGO<span>WEB</span></div>
  <h2>Masuk</h2>
  <p class="sub">Akses dashboard dan riwayat kamu</p>

  <div class="card">
    <?php if (isset($_SESSION['error'])): ?>
      <div class="alert alert-error"><?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['success'])): ?>
      <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?></div>
    <?php endif; ?>

    <form method="POST" action="../includes/auth.php">
      <input type="hidden" name="action" value="login">
      <label>Email</label>
      <input type="email" name="email" placeholder="email@kamu.com" required autocomplete="email">
      <label>Password</label>
      <input type="password" name="password" placeholder="••••••••" required autocomplete="current-password">
      <button type="submit" class="btn">Masuk →</button>
    </form>
  </div>

  <div class="links">
    Belum punya akun? <a href="register.php">Daftar sekarang</a>
  </div>
</div>
</body>
</html>