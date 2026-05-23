<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Daftar — StegoWeb</title>
<link href="https://fonts.googleapis.com/css2?family=Space+Mono:wght@400;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
:root { --bg:#fafafa;--surface:#ffffff;--border:#f0e0e8;--accent:#e8637f;--danger:#e05555;--text:#2a2a2a;--muted:#888888;--mono:'Space Mono',monospace;--sans:'DM Sans',sans-serif; }
* { box-sizing:border-box;margin:0;padding:0; }
body { background:var(--bg);color:var(--text);font-family:var(--sans);min-height:100vh;display:flex;flex-direction:column;align-items:center;justify-content:center;padding:1.5rem; }
.box { width:100%;max-width:420px; }
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
.alert-error { padding:0.65rem 0.875rem;border-radius:8px;font-size:0.825rem;margin-bottom:1rem;background:#fff0f0;border:1px solid #f5c8c8;color:#e05555; }
.hint { font-size:0.73rem;color:var(--muted);margin-top:0.3rem; }
.strength { height:3px;border-radius:2px;margin-top:0.4rem;background:var(--border);overflow:hidden; }
.strength-fill { height:100%;border-radius:2px;transition:width 0.3s,background 0.3s; }
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
  <h2>Buat Akun</h2>
  <p class="sub">Gratis — simpan semua riwayat encode & decode</p>

  <div class="card">
    <?php if (isset($_SESSION['error'])): ?>
      <div class="alert-error"><?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <form method="POST" action="../includes/auth.php">
      <input type="hidden" name="action" value="register">
      <label>Username</label>
      <input type="text" name="username" placeholder="namakamu" required minlength="3" maxlength="50" autocomplete="username">
      <label>Email</label>
      <input type="email" name="email" placeholder="email@kamu.com" required autocomplete="email">
      <label>Password</label>
      <input type="password" name="password" id="pwd" placeholder="Minimal 8 karakter" required minlength="8" oninput="checkStrength(this.value)" autocomplete="new-password">
      <div class="strength"><div class="strength-fill" id="sf" style="width:0%"></div></div>
      <div class="hint" id="pwd-hint">Masukkan password</div>
      <label>Konfirmasi Password</label>
      <input type="password" name="confirm_password" placeholder="Ulangi password" required autocomplete="new-password">
      <button type="submit" class="btn">Daftar →</button>
    </form>
  </div>

  <div class="links">
    Sudah punya akun? <a href="login.php">Masuk di sini</a>
  </div>
</div>

<script>
function checkStrength(v) {
  let score = 0;
  if (v.length >= 8)  score++;
  if (/[A-Z]/.test(v)) score++;
  if (/[0-9]/.test(v)) score++;
  if (/[^a-zA-Z0-9]/.test(v)) score++;
  const colors = ['#f4604a','#f4604a','#ffb432','#4af4a0','#4af4a0'];
  const labels = ['','Lemah','Sedang','Kuat','Sangat kuat'];
  document.getElementById('sf').style.width = (score * 25) + '%';
  document.getElementById('sf').style.background = colors[score];
  document.getElementById('pwd-hint').textContent = labels[score];
}
</script>
</body>
</html>