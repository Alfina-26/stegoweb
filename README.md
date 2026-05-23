# StegoWeb — Panduan Instalasi

## Prasyarat
- XAMPP / Laragon / WAMP (PHP 8.0+ dengan ekstensi GD)
- Browser modern

## Langkah Instalasi

### 1. Copy project ke htdocs
```
xampp/htdocs/stegoweb/
```

### 2. Buat database
Buka phpMyAdmin → klik "Import" → pilih file `database.sql` → klik "Go"

### 3. Sesuaikan konfigurasi (jika perlu)
Edit `includes/config.php`:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');        // password MySQL kamu
define('DB_NAME', 'stegoweb');
```

### 4. Buat folder upload
Pastikan folder `assets/uploads/stego/` ada dan bisa ditulis (writable).
Di Windows/XAMPP biasanya otomatis. Di Linux:
```bash
chmod 755 assets/uploads/stego/
```

### 5. Aktifkan ekstensi GD di PHP
Di `php.ini`, pastikan baris ini tidak dikomentari:
```
extension=gd
```

### 6. Akses aplikasi
Buka browser → http://localhost/stegoweb

---

## Struktur Folder
```
stegoweb/
├── index.php               ← Halaman utama (encode/decode)
├── database.sql            ← Schema database
├── assets/
│   ├── uploads/stego/      ← Gambar stego hasil encode
├── includes/
│   ├── config.php          ← Konfigurasi & koneksi DB
│   ├── auth.php            ← Handler login/register/logout
│   └── stego.php           ← API encode/decode + PSNR
└── pages/
    ├── login.php           ← Halaman login
    ├── register.php        ← Halaman daftar
    └── dashboard.php       ← Dashboard member
```

## Fitur
| Fitur | Guest | Member |
|---|---|---|
| Encode gambar | ✓ | ✓ |
| Decode gambar | ✓ | ✓ |
| Kunci enkripsi XOR | ✓ | ✓ |
| Analisis PSNR & MSE | ✓ | ✓ |
| Riwayat tersimpan | ✗ | ✓ |
| Dashboard statistik | ✗ | ✓ |

## Format gambar yang didukung
- PNG (direkomendasikan — lossless)
- BMP

> ⚠️ Jangan gunakan JPEG — kompresi lossy akan merusak bit LSB!
