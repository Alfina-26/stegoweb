<?php ob_start(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>StegoWeb — Steganografi LSB</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Space+Mono:wght@400;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
:root {
  --bg:      #fafafa;
  --surface: #ffffff;
  --border:  #f0e0e8;
  --accent:  #e8637f;
  --accent2: #c0607a;
  --danger:  #e05555;
  --text:    #2a2a2a;
  --muted:   #888888;
  --mono:    'Space Mono', monospace;
  --sans:    'DM Sans', sans-serif;
}
* { box-sizing: border-box; margin: 0; padding: 0; }
body { background: var(--bg); color: var(--text); font-family: var(--sans); min-height: 100vh; }

/* NAV */
nav { display: flex; align-items: center; justify-content: space-between; padding: 1rem 2rem; border-bottom: 1px solid var(--border); position: sticky; top: 0; background: rgba(255,255,255,0.95); backdrop-filter: blur(12px); z-index: 100; }
.nav-logo { font-family: var(--mono); font-size: 1rem; color: #c0607a; letter-spacing: 0.05em; }
.nav-logo span { color: var(--muted); }
.nav-links { display: flex; align-items: center; gap: 1rem; }
.nav-links a { font-size: 0.85rem; color: var(--muted); text-decoration: none; transition: color 0.2s; }
.nav-links a:hover { color: var(--text); }
.btn { font-family: var(--sans); font-size: 0.85rem; padding: 0.5rem 1.2rem; border-radius: 8px; cursor: pointer; transition: all 0.2s; border: none; font-weight: 500; }
.btn-ghost { background: transparent; border: 1px solid var(--border); color: var(--text); }
.btn-ghost:hover { border-color: var(--accent); color: var(--accent); }
.btn-accent { background: #e8637f; color: #ffffff; font-weight: 600; }
.btn-accent:hover { filter: brightness(1.1); transform: translateY(-1px); }
.btn-danger { background: var(--danger); color: #fff; font-weight: 600; }

/* HERO */
.hero { padding: 3rem 2rem 2rem; text-align: center; }
.hero-badge { display: inline-flex; align-items: center; gap: 0.4rem; background: #fdeef2; border: 1px solid #f5c8d4; color: #c0607a; font-size: 0.75rem; font-family: var(--mono); padding: 0.3rem 0.8rem; border-radius: 20px; margin-bottom: 1.5rem; }
.hero h1 { font-size: clamp(1.8rem, 4vw, 3rem); font-weight: 600; line-height: 1.2; margin-bottom: 0.8rem; }
.hero h1 em { font-style: normal; color: var(--accent); }
.hero p { color: var(--muted); font-size: 0.95rem; max-width: 480px; margin: 0 auto 1rem; line-height: 1.7; }
.guest-notice { display: inline-flex; align-items: center; gap: 0.5rem; background: #fdeef2; border: 1px solid #f5c8d4; color: #c0607a; font-size: 0.8rem; padding: 0.4rem 1rem; border-radius: 20px; margin-bottom: 2rem; }

/* TABS */
.tab-wrap { max-width: 860px; margin: 0 auto; padding: 0 1.5rem 3rem; }
.tabs { display: flex; gap: 0; background: var(--surface); border: 1px solid var(--border); border-radius: 12px; padding: 4px; margin-bottom: 1.5rem; width: fit-content; }
.tab { font-family: var(--sans); font-size: 0.875rem; padding: 0.5rem 1.4rem; background: none; border: none; color: var(--muted); cursor: pointer; border-radius: 9px; transition: all 0.18s; font-weight: 500; }
.tab.active { background: #fff; color: #c0607a; box-shadow: 0 1px 3px rgba(0,0,0,0.08); }

/* PANEL */
.panel { display: none; animation: fadeIn 0.2s ease; }
.panel.active { display: block; }
@keyframes fadeIn { from { opacity: 0; transform: translateY(6px); } to { opacity: 1; transform: translateY(0); } }

/* GRID */
.grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }

/* CARD */
.card { background: var(--surface); border: 1px solid var(--border); border-radius: 14px; padding: 1.25rem; }
.card-label { font-family: var(--mono); font-size: 0.7rem; color: var(--muted); letter-spacing: 0.08em; margin-bottom: 0.75rem; }

/* UPLOAD */
.upload-zone { border: 1.5px dashed #f0c8d4; border-radius: 10px; padding: 2rem 1rem; text-align: center; cursor: pointer; transition: all 0.2s; min-height: 140px; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 0.5rem; }
.upload-zone:hover, .upload-zone.drag { border-color: var(--accent); background: rgba(74,244,160,0.04); }
.upload-zone svg { color: var(--muted); }
.upload-zone .u-title { font-size: 0.875rem; font-weight: 500; }
.upload-zone .u-sub { font-size: 0.75rem; color: var(--muted); }
.preview-img { width: 100%; height: 150px; object-fit: cover; border-radius: 8px; display: block; }

/* INPUTS */
textarea, input[type=text], input[type=password] {
  width: 100%; background: var(--bg); border: 1px solid var(--border); border-radius: 8px;
  padding: 0.65rem 0.875rem; color: var(--text); font-family: var(--sans); font-size: 0.875rem;
  transition: border-color 0.2s; outline: none;
}
textarea:focus, input:focus { border-color: var(--accent); }
textarea { resize: none; line-height: 1.6; }
textarea::placeholder, input::placeholder { color: var(--muted); }
label.field-label { display: block; font-size: 0.78rem; color: var(--muted); margin-bottom: 0.4rem; margin-top: 0.75rem; }

/* PROGRESS */
.progress-wrap { margin-top: 0.75rem; }
.prog-meta { display: flex; justify-content: space-between; font-size: 0.75rem; color: var(--muted); margin-bottom: 0.3rem; }
.prog-bar { height: 4px; background: var(--border); border-radius: 2px; overflow: hidden; }
.prog-fill { height: 100%; background: var(--accent); border-radius: 2px; transition: width 0.3s; }

/* ACTION ROW */
.action-row { display: flex; justify-content: flex-end; margin-top: 1rem; gap: 0.5rem; }

/* RESULT */
.result-card { background: var(--bg); border: 1px solid var(--border); border-radius: 10px; padding: 1rem; margin-top: 1rem; }
.result-card .r-label { font-family: var(--mono); font-size: 0.7rem; color: var(--accent); margin-bottom: 0.5rem; }
.stat-row { display: grid; grid-template-columns: repeat(3,1fr); gap: 8px; margin-top: 0.75rem; }
.stat { background: var(--bg); border: 1px solid var(--border); border-radius: 8px; padding: 0.75rem; text-align: center; }
.stat .s-val { font-family: var(--mono); font-size: 1.1rem; color: var(--accent); font-weight: 700; }
.stat .s-key { font-size: 0.7rem; color: var(--muted); margin-top: 2px; }

/* ALERT */
.alert { display: flex; align-items: center; gap: 0.5rem; padding: 0.65rem 1rem; border-radius: 8px; font-size: 0.825rem; margin-top: 0.75rem; }
.alert-success { background: rgba(74,244,160,0.08); border: 1px solid rgba(74,244,160,0.2); color: var(--accent); }
.alert-error   { background: rgba(244,96,74,0.08);  border: 1px solid rgba(244,96,74,0.2);  color: var(--danger); }
.alert-info    { background: #fdeef2; border: 1px solid #f5c8d4; color: #c0607a; }
.hidden { display: none; }

/* SPINNER */
.spinner { width: 16px; height: 16px; border: 2px solid transparent; border-top-color: currentColor; border-radius: 50%; animation: spin 0.6s linear infinite; display: inline-block; }
@keyframes spin { to { transform: rotate(360deg); } }

/* FOOTER */
footer { border-top: 1px solid var(--border); padding: 1.5rem 2rem; text-align: center; color: var(--muted); font-size: 0.8rem; }
footer span { color: var(--accent); font-family: var(--mono); }
</style>
</head>
<body>
<?php require_once 'includes/config.php'; ?>

<nav>
  <div class="nav-logo">STEGO<span>WEB</span></div>
  <div class="nav-links">
    <a href="index.php">Beranda</a>
    <?php if (isLoggedIn()): ?>
      <a href="pages/dashboard.php">Dashboard</a>
      <a href="includes/auth.php" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Keluar</a>
      <form id="logout-form" action="includes/auth.php" method="POST" style="display:none"><input type="hidden" name="action" value="logout"></form>
    <?php else: ?>
      <a href="pages/login.php">Masuk</a>
      <a href="pages/register.php" class="btn btn-accent">Daftar</a>
    <?php endif; ?>
  </div>
</nav>

<div class="hero">
  <div class="hero-badge">
    <svg width="10" height="10" viewBox="0 0 10 10" fill="currentColor"><circle cx="5" cy="5" r="5"/></svg>
    Steganografi LSB — Proyek Keamanan Siber
  </div>
  <h1>Sembunyikan pesan<br>dalam <em>gambar biasa</em></h1>
  <p>Teknik Least Significant Bit (LSB) menyisipkan pesan ke dalam pixel tanpa perubahan visual yang terdeteksi.</p>

  <?php if (!isLoggedIn()): ?>
  <div class="guest-notice">
    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
    Mode Tamu — riwayat tidak disimpan. <a href="pages/register.php" style="color:inherit;font-weight:600;margin-left:4px">Daftar gratis →</a>
  </div>
  <?php endif; ?>
</div>

<!-- MAIN APP -->
<div class="tab-wrap">
  <div class="tabs">
    <button class="tab active" onclick="switchTab('encode')">🔒 Encode</button>
    <button class="tab" onclick="switchTab('decode')">🔓 Decode</button>
    <button class="tab" onclick="switchTab('analisis')">📊 Analisis</button>
  </div>

  <!-- ENCODE -->
  <div class="panel active" id="panel-encode">
    <div class="grid-2">
      <div class="card">
        <div class="card-label">// GAMBAR ASLI (COVER IMAGE)</div>
        <div class="upload-zone" id="drop-encode" onclick="document.getElementById('file-encode').click()">
          <svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="3"/><path d="M3 15l5-5 4 4 3-3 6 6"/><circle cx="8.5" cy="8.5" r="1.5"/></svg>
          <div class="u-title">Klik atau seret gambar</div>
          <div class="u-sub">PNG / BMP · maks 5 MB</div>
        </div>
        <input type="file" id="file-encode" accept=".png,.bmp" style="display:none" onchange="previewEncode(this)">
        <div id="preview-encode" style="margin-top:0.75rem;display:none">
          <img id="img-encode" class="preview-img" src="" alt="preview">
          <div class="stat-row" style="margin-top:0.5rem">
            <div class="stat"><div class="s-val" id="enc-w">—</div><div class="s-key">Lebar (px)</div></div>
            <div class="stat"><div class="s-val" id="enc-h">—</div><div class="s-key">Tinggi (px)</div></div>
            <div class="stat"><div class="s-val" id="enc-cap">—</div><div class="s-key">Kapasitas</div></div>
          </div>
        </div>
      </div>

      <div class="card">
        <div class="card-label">// PESAN RAHASIA</div>
        <textarea id="enc-message" rows="5" placeholder="Tulis pesan yang ingin disembunyikan..." oninput="updateProgress()"></textarea>
        <div class="progress-wrap">
          <div class="prog-meta"><span>Kapasitas terpakai</span><span id="prog-label">0 / 0 karakter</span></div>
          <div class="prog-bar"><div class="prog-fill" id="prog-fill" style="width:0%"></div></div>
        </div>
        <label class="field-label">Kunci enkripsi XOR <span style="color:var(--muted);font-size:0.7rem">(opsional)</span></label>
        <input type="password" id="enc-key" placeholder="Kunci rahasia...">
        <div class="action-row">
          <button class="btn btn-accent" onclick="doEncode()">
            <span id="enc-btn-text">🔒 Encode & Simpan</span>
            <span class="spinner hidden" id="enc-spinner"></span>
          </button>
        </div>
        <div id="enc-alert" class="hidden"></div>
      </div>
    </div>

    <!-- Hasil encode -->
    <div class="card hidden" id="enc-result" style="margin-top:1rem">
      <div class="card-label">// HASIL ENCODE</div>
      <div class="grid-2">
        <div>
          <img id="img-stego" class="preview-img" src="" alt="Gambar stego">
        </div>
        <div>
          <div class="stat-row" style="grid-template-columns:1fr 1fr">
            <div class="stat"><div class="s-val" id="res-psnr">—</div><div class="s-key">PSNR (dB)</div></div>
            <div class="stat"><div class="s-val" id="res-mse">—</div><div class="s-key">MSE</div></div>
          </div>
          <div class="alert alert-success" style="margin-top:0.75rem">✓ Pesan berhasil disisipkan!</div>
          <div class="action-row">
            <a id="dl-link" href="#" download class="btn btn-accent" style="text-decoration:none">⬇ Unduh Stego</a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- DECODE -->
  <div class="panel" id="panel-decode">
    <div class="grid-2">
      <div class="card">
        <div class="card-label">// GAMBAR STEGO (INPUT)</div>
        <div class="upload-zone" onclick="document.getElementById('file-decode').click()">
          <svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
          <div class="u-title">Upload gambar stego</div>
          <div class="u-sub">Gambar yang mengandung pesan</div>
        </div>
        <input type="file" id="file-decode" accept=".png,.bmp" style="display:none" onchange="previewDecode(this)">
        <div id="preview-decode" style="margin-top:0.75rem;display:none">
          <img id="img-decode" class="preview-img" src="" alt="preview decode">
        </div>
      </div>

      <div class="card">
        <div class="card-label">// PENGATURAN DECODE</div>
        <label class="field-label">Kunci enkripsi XOR <span style="color:var(--muted);font-size:0.7rem">(jika ada)</span></label>
        <input type="password" id="dec-key" placeholder="Kunci yang sama saat encode...">
        <div style="margin-top:1.5rem;padding:1rem;background:var(--bg);border-radius:8px;border:1px solid var(--border)">
          <div style="font-size:0.75rem;color:var(--muted);margin-bottom:0.4rem;font-family:var(--mono)">INFO</div>
          <div style="font-size:0.8rem;color:var(--muted);line-height:1.6">Upload gambar PNG hasil encode lalu klik Decode. Pesan tersembunyi akan diekstrak dari bit LSB setiap pixel.</div>
        </div>
        <div class="action-row">
          <button class="btn btn-accent" onclick="doDecode()">
            <span id="dec-btn-text">🔓 Decode Pesan</span>
            <span class="spinner hidden" id="dec-spinner"></span>
          </button>
        </div>
        <div id="dec-alert" class="hidden"></div>
      </div>
    </div>

    <div class="card hidden" id="dec-result" style="margin-top:1rem">
      <div class="card-label">// PESAN DITEMUKAN</div>
      <div style="background:var(--bg);border:1px solid var(--border);border-radius:8px;padding:1rem;font-family:var(--mono);font-size:0.85rem;line-height:1.7;color:var(--accent);word-break:break-all" id="dec-msg"></div>
      <div class="stat-row" style="margin-top:0.75rem;grid-template-columns:1fr 1fr">
        <div class="stat"><div class="s-val" id="dec-len">—</div><div class="s-key">Panjang pesan</div></div>
        <div class="stat"><div class="s-val">LSB</div><div class="s-key">Metode</div></div>
      </div>
    </div>
  </div>

  <!-- ANALISIS -->
  <div class="panel" id="panel-analisis">
    <div class="card">
      <div class="card-label">// PANDUAN NILAI PSNR</div>
      <div style="display:flex;flex-direction:column;gap:0.5rem">
        <div style="display:flex;align-items:center;gap:1rem;padding:0.75rem;background:rgba(244,96,74,0.06);border:1px solid rgba(244,96,74,0.15);border-radius:8px">
          <div style="font-family:var(--mono);font-size:0.9rem;color:var(--danger);min-width:80px">&lt; 30 dB</div>
          <div style="font-size:0.825rem;color:var(--muted)">Perubahan pixel terlihat jelas oleh mata manusia — tidak aman</div>
        </div>
        <div style="display:flex;align-items:center;gap:1rem;padding:0.75rem;background:rgba(255,180,50,0.06);border:1px solid rgba(255,180,50,0.15);border-radius:8px">
          <div style="font-family:var(--mono);font-size:0.9rem;color:#ffb432;min-width:80px">30–40 dB</div>
          <div style="font-size:0.825rem;color:var(--muted)">Perubahan sangat kecil, hampir tidak terlihat</div>
        </div>
        <div style="display:flex;align-items:center;gap:1rem;padding:0.75rem;background:rgba(74,244,160,0.06);border:1px solid rgba(74,244,160,0.15);border-radius:8px">
          <div style="font-family:var(--mono);font-size:0.9rem;color:var(--accent);min-width:80px">&gt; 40 dB</div>
          <div style="font-size:0.825rem;color:var(--muted)">Tidak terdeteksi secara visual — ideal untuk steganografi ✓</div>
        </div>
      </div>
    </div>

    <div class="card" style="margin-top:1rem">
      <div class="card-label">// RUMUS PSNR & MSE</div>
      <div style="background:var(--bg);border:1px solid var(--border);border-radius:8px;padding:1rem;font-family:var(--mono);font-size:0.82rem;line-height:2;color:var(--accent2)">
        MSE  = (1/N) × Σ [I(x,y) − I'(x,y)]²<br>
        PSNR = 10 × log₁₀(255² / MSE)  [dB]<br>
        <span style="color:var(--muted);font-size:0.75rem">I = gambar asli · I' = gambar stego · N = total pixel × 3 channel</span>
      </div>
    </div>

    <div class="alert alert-info" style="margin-top:1rem">
      ℹ Lakukan encode dulu untuk mendapatkan nilai PSNR dan MSE otomatis dari sistem.
    </div>
  </div>
</div>

<footer>
  Dibuat oleh <span>Kelompok 3</span> · Mata Kuliah Keamanan Data Dan Informasi · <?= date('Y') ?>
</footer>

<script>
let encFile = null, decFile = null;
let encCapacity = 0;

function switchTab(name) {
  document.querySelectorAll('.tab').forEach((t, i) => {
    t.classList.toggle('active', ['encode','decode','analisis'][i] === name);
  });
  document.querySelectorAll('.panel').forEach(p => p.classList.remove('active'));
  document.getElementById('panel-' + name).classList.add('active');
}

function previewEncode(input) {
  encFile = input.files[0];
  if (!encFile) return;
  const url = URL.createObjectURL(encFile);
  document.getElementById('img-encode').src = url;
  document.getElementById('preview-encode').style.display = 'block';
  const img = new Image();
  img.onload = () => {
    const cap = Math.floor((img.width * img.height * 3) / 8) - 4;
    encCapacity = cap;
    document.getElementById('enc-w').textContent   = img.width;
    document.getElementById('enc-h').textContent   = img.height;
    document.getElementById('enc-cap').textContent = cap + ' B';
    updateProgress();
  };
  img.src = url;
}

function previewDecode(input) {
  decFile = input.files[0];
  if (!decFile) return;
  document.getElementById('img-decode').src = URL.createObjectURL(decFile);
  document.getElementById('preview-decode').style.display = 'block';
}

function updateProgress() {
  const len = document.getElementById('enc-message').value.length;
  const cap = encCapacity || 0;
  const pct = cap ? Math.min(100, Math.round(len / cap * 100)) : 0;
  document.getElementById('prog-fill').style.width  = pct + '%';
  document.getElementById('prog-label').textContent = len + ' / ' + cap + ' karakter';
}

function showAlert(id, type, msg) {
  const el = document.getElementById(id);
  el.className = 'alert alert-' + type;
  el.textContent = msg;
  el.classList.remove('hidden');
}

async function doEncode() {
  if (!encFile) { showAlert('enc-alert','error','Upload gambar terlebih dahulu.'); return; }
  const msg = document.getElementById('enc-message').value.trim();
  if (!msg)   { showAlert('enc-alert','error','Pesan tidak boleh kosong.'); return; }

  const btn = document.getElementById('enc-btn-text');
  const sp  = document.getElementById('enc-spinner');
  btn.classList.add('hidden'); sp.classList.remove('hidden');

  const fd = new FormData();
  fd.append('action', 'encode');
  fd.append('image',   encFile);
  fd.append('message', msg);
  fd.append('key',     document.getElementById('enc-key').value);

  try {
    const res  = await fetch('includes/stego.php', { method: 'POST', body: fd });
    const data = await res.json();
    if (data.success) {
      document.getElementById('enc-result').classList.remove('hidden');
      document.getElementById('img-stego').src      = data.file;
      document.getElementById('dl-link').href        = data.file;
      document.getElementById('res-psnr').textContent = data.psnr + ' dB';
      document.getElementById('res-mse').textContent  = data.mse;
      showAlert('enc-alert','success','✓ Berhasil! PSNR: ' + data.psnr + ' dB');
    } else {
      showAlert('enc-alert','error', data.message);
    }
  } catch(e) {
    showAlert('enc-alert','error','Terjadi kesalahan jaringan.');
  }
  btn.classList.remove('hidden'); sp.classList.add('hidden');
}

async function doDecode() {
  if (!decFile) { showAlert('dec-alert','error','Upload gambar stego terlebih dahulu.'); return; }

  const btn = document.getElementById('dec-btn-text');
  const sp  = document.getElementById('dec-spinner');
  btn.classList.add('hidden'); sp.classList.remove('hidden');

  const fd = new FormData();
  fd.append('action', 'decode');
  fd.append('image',   decFile);
  fd.append('key',     document.getElementById('dec-key').value);

  try {
    const res  = await fetch('includes/stego.php', { method: 'POST', body: fd });
    const data = await res.json();
    if (data.success) {
      document.getElementById('dec-result').classList.remove('hidden');
      document.getElementById('dec-msg').textContent = data.message;
      document.getElementById('dec-len').textContent = data.msg_length + ' karakter';
      showAlert('dec-alert','success','✓ Pesan berhasil diekstrak!');
    } else {
      showAlert('dec-alert','error', data.message);
    }
  } catch(e) {
    showAlert('dec-alert','error','Terjadi kesalahan jaringan.');
  }
  btn.classList.remove('hidden'); sp.classList.add('hidden');
}

// Drag & drop
const dropZone = document.getElementById('drop-encode');
dropZone.addEventListener('dragover', e => { e.preventDefault(); dropZone.classList.add('drag'); });
dropZone.addEventListener('dragleave', () => dropZone.classList.remove('drag'));
dropZone.addEventListener('drop', e => {
  e.preventDefault(); dropZone.classList.remove('drag');
  const input = document.getElementById('file-encode');
  input.files = e.dataTransfer.files;
  previewEncode(input);
});
</script>
</body>
</html>