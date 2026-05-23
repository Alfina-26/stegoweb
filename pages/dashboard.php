<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard — StegoWeb</title>
<link href="https://fonts.googleapis.com/css2?family=Space+Mono:wght@400;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
:root { --bg:#fafafa;--surface:#ffffff;--border:#f0e0e8;--accent:#e8637f;--accent2:#c0607a;--danger:#e05555;--text:#2a2a2a;--muted:#888888;--mono:'Space Mono',monospace;--sans:'DM Sans',sans-serif; }
* { box-sizing:border-box;margin:0;padding:0; }
body { background:var(--bg);color:var(--text);font-family:var(--sans);min-height:100vh; }
nav { display:flex;align-items:center;justify-content:space-between;padding:1rem 2rem;border-bottom:1px solid var(--border);position:sticky;top:0;background:rgba(255,255,255,0.95);backdrop-filter:blur(12px);z-index:100; }
.nav-logo { font-family:var(--mono);font-size:1rem;color:var(--accent); }
.nav-logo span { color:var(--muted); }
.nav-right { display:flex;align-items:center;gap:1rem; }
.nav-right a { font-size:0.85rem;color:var(--muted);text-decoration:none;transition:color 0.2s; }
.nav-right a:hover { color:var(--text); }
.btn { font-family:var(--sans);font-size:0.85rem;padding:0.45rem 1rem;border-radius:8px;cursor:pointer;transition:all 0.2s;border:1px solid var(--border);background:transparent;color:var(--text); }
.btn:hover { border-color:var(--danger);color:var(--danger); }
main { max-width:900px;margin:0 auto;padding:2rem 1.5rem; }
.greeting { margin-bottom:2rem; }
.greeting h1 { font-size:1.6rem;font-weight:600; }
.greeting h1 em { font-style:normal;color:var(--accent); }
.greeting p { color:var(--muted);font-size:0.875rem;margin-top:0.3rem; }
.stat-grid { display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;margin-bottom:2rem; }
.stat-card { background:var(--surface);border:1px solid var(--border);border-radius:12px;padding:1.25rem; }
.stat-card .sc-val { font-family:var(--mono);font-size:1.8rem;font-weight:700;color:var(--accent); }
.stat-card .sc-key { font-size:0.8rem;color:var(--muted);margin-top:0.3rem; }
.section-title { font-family:var(--mono);font-size:0.75rem;color:var(--muted);letter-spacing:0.08em;margin-bottom:1rem; }
.history-table { width:100%;border-collapse:collapse; }
.history-table th { font-size:0.75rem;color:var(--muted);font-weight:500;padding:0.5rem 0.75rem;text-align:left;border-bottom:1px solid var(--border); }
.history-table td { padding:0.75rem;font-size:0.85rem;border-bottom:1px solid rgba(37,40,48,0.6); }
.history-table tr:last-child td { border-bottom:none; }
.badge { display:inline-block;padding:0.2rem 0.6rem;border-radius:20px;font-size:0.7rem;font-family:var(--mono); }
.badge-encode { background:#fdeef2;color:#c0607a;border:1px solid #f5c8d4; }
.badge-decode { background:#fce8f0;color:#a04060;border:1px solid #f0c0d0; }
.psnr-good { color:var(--accent); }
.psnr-mid  { color:#ffb432; }
.psnr-bad  { color:var(--danger); }
.empty-state { text-align:center;padding:3rem;color:var(--muted); }
.empty-state a { color:var(--accent);text-decoration:none;font-weight:500; }
.cta { display:inline-flex;align-items:center;gap:0.4rem;background:#e8637f;color:#fff;border:none;border-radius:8px;padding:0.5rem 1.2rem;font-family:var(--sans);font-size:0.875rem;font-weight:600;cursor:pointer;text-decoration:none;transition:filter 0.2s; }
.cta:hover { filter:brightness(1.1); }
</style>
</head>
<body>
<?php
require_once '../includes/config.php';
if (!isLoggedIn()) redirect('../pages/login.php');

$db = getDB();
$uid = $_SESSION['user_id'];

// Statistik
$stats = $db->prepare("SELECT
  COUNT(*) AS total,
  SUM(type='encode') AS encodes,
  SUM(type='decode') AS decodes,
  AVG(psnr_value) AS avg_psnr
  FROM history WHERE user_id = ?");
$stats->execute([$uid]);
$s = $stats->fetch();

// Riwayat terbaru (dengan nama user)
$hist = $db->prepare("SELECT h.*, u.username AS user FROM history h JOIN users u ON h.user_id = u.id WHERE h.user_id = ? ORDER BY h.created_at DESC LIMIT 20");
$hist->execute([$uid]);
$rows = $hist->fetchAll();
?>

<nav>
  <div class="nav-logo">STEGO<span>WEB</span></div>
  <div class="nav-right">
    <a href="../index.php">← Encode / Decode</a>
    <span style="color:var(--muted);font-size:0.85rem"><?= htmlspecialchars($_SESSION['username']) ?></span>
    <form method="POST" action="../includes/auth.php" style="display:inline">
      <input type="hidden" name="action" value="logout">
      <button type="submit" class="btn">Keluar</button>
    </form>
  </div>
</nav>

<main>
  <div class="greeting">
    <h1>Halo, <em><?= htmlspecialchars($_SESSION['username']) ?></em> 👋</h1>
    <p>Berikut ringkasan aktivitas steganografi kamu.</p>
  </div>

  <div class="stat-grid">
    <div class="stat-card">
      <div class="sc-val"><?= (int)$s['total'] ?></div>
      <div class="sc-key">Total sesi</div>
    </div>
    <div class="stat-card">
      <div class="sc-val"><?= (int)$s['encodes'] ?></div>
      <div class="sc-key">Encode dilakukan</div>
    </div>
    <div class="stat-card">
      <div class="sc-val"><?= $s['avg_psnr'] ? round($s['avg_psnr'],1).' dB' : '—' ?></div>
      <div class="sc-key">Rata-rata PSNR</div>
    </div>
  </div>

  <div class="section-title">// RIWAYAT ENCODE & DECODE</div>

  <?php if (empty($rows)): ?>
    <div class="empty-state">
      <p style="font-size:2rem;margin-bottom:0.75rem">🔒</p>
      <p>Belum ada riwayat. Coba encode gambar pertama kamu!</p>
      <a href="../index.php" style="display:inline-block;margin-top:1rem" class="cta">Mulai Encode →</a>
    </div>
  <?php else: ?>
    <table class="history-table">
      <thead>
        <tr>
          <th>Tipe</th>
          <th>User</th>
          <th>File</th>
          <th>Panjang Pesan</th>
          <th>PSNR</th>
          <th>Waktu</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($rows as $r): ?>
        <tr>
          <td><span class="badge badge-<?= $r['type'] ?>"><?= strtoupper($r['type']) ?></span></td>
          <td style="color:var(--accent);font-weight:500"><?= htmlspecialchars($r['user']) ?></td>
          <td style="color:var(--muted)"><?= htmlspecialchars($r['original_file'] ?? '—') ?></td>
          <td><?= $r['msg_length'] ? $r['msg_length'].' karakter' : '—' ?></td>
          <td>
            <?php if ($r['psnr_value']): ?>
              <span class="<?= $r['psnr_value']>=40 ? 'psnr-good' : ($r['psnr_value']>=30 ? 'psnr-mid' : 'psnr-bad') ?>">
                <?= round($r['psnr_value'],1) ?> dB
              </span>
            <?php else: echo '—'; endif; ?>
          </td>
          <td style="color:var(--muted);font-size:0.8rem"><?= date('d M Y H:i', strtotime($r['created_at'])) ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</main>
</body>
</html>