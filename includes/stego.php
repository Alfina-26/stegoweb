<?php
require_once __DIR__ . '/../includes/config.php';
header('Content-Type: application/json');

$action = $_POST['action'] ?? '';

// ── ENCODE ────────────────────────────────────────────────
if ($action === 'encode') {
    if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(['success' => false, 'message' => 'Gagal upload gambar.']);
        exit;
    }

    $file     = $_FILES['image'];
    $message  = $_POST['message'] ?? '';
    $key      = $_POST['key'] ?? '';

    // Validasi tipe file
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime  = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!in_array($mime, ALLOWED_TYPES)) {
        echo json_encode(['success' => false, 'message' => 'Hanya file PNG atau BMP yang diizinkan.']);
        exit;
    }
    if ($file['size'] > MAX_FILE_SIZE) {
        echo json_encode(['success' => false, 'message' => 'Ukuran file melebihi 5 MB.']);
        exit;
    }
    if (empty($message)) {
        echo json_encode(['success' => false, 'message' => 'Pesan tidak boleh kosong.']);
        exit;
    }

    // Enkripsi pesan dengan XOR jika ada kunci
    $finalMsg = $key ? xorEncrypt($message, $key) : $message;

    // Load gambar
    $img = imagecreatefrompng($file['tmp_name']);
    if (!$img) {
        echo json_encode(['success' => false, 'message' => 'Gagal membaca gambar.']);
        exit;
    }

    $width  = imagesx($img);
    $height = imagesy($img);
    $capacity = intval(($width * $height * 3) / 8) - 4; // 4 byte header panjang

    if (strlen($finalMsg) > $capacity) {
        echo json_encode(['success' => false, 'message' => "Pesan terlalu panjang. Maks $capacity karakter untuk gambar ini."]);
        exit;
    }

    // Sisipkan pesan dengan LSB
    $imgCopy = imagecreatetruecolor($width, $height);
    imagecopy($imgCopy, $img, 0, 0, 0, 0, $width, $height);

    $binaryMsg = toBinary(strlen($finalMsg), 32) . strToBinary($finalMsg);
    $bitIdx    = 0;
    $totalBits = strlen($binaryMsg);

    outer:
    for ($y = 0; $y < $height && $bitIdx < $totalBits; $y++) {
        for ($x = 0; $x < $width && $bitIdx < $totalBits; $x++) {
            $rgb = imagecolorat($imgCopy, $x, $y);
            $r   = ($rgb >> 16) & 0xFF;
            $g   = ($rgb >> 8)  & 0xFF;
            $b   =  $rgb        & 0xFF;

            foreach (['r','g','b'] as $ch) {
                if ($bitIdx >= $totalBits) break;
                $$ch = ($$ch & 0xFE) | (int)$binaryMsg[$bitIdx];
                $bitIdx++;
            }

            $newColor = imagecolorallocate($imgCopy, $r, $g, $b);
            imagesetpixel($imgCopy, $x, $y, $newColor);
        }
    }

    // Hitung PSNR & MSE
    [$psnr, $mse] = calcPSNR($img, $imgCopy, $width, $height);

    // Simpan gambar stego
    if (!is_dir(UPLOAD_DIR)) mkdir(UPLOAD_DIR, 0755, true);
    $outName = 'stego_' . uniqid() . '.png';
    $outPath = UPLOAD_DIR . $outName;
    imagepng($imgCopy, $outPath, 0);

    imagedestroy($img);
    imagedestroy($imgCopy);

    // Simpan riwayat jika login
    if (isLoggedIn()) {
        $db   = getDB();
        $stmt = $db->prepare("INSERT INTO history (user_id, type, original_file, stego_file, msg_length, psnr_value, mse_value) VALUES (?,?,?,?,?,?,?)");
        $stmt->execute([$_SESSION['user_id'], 'encode', $file['name'], $outName, strlen($message), $psnr, $mse]);
    }

    echo json_encode([
        'success'    => true,
        'file'       => 'assets/uploads/stego/' . $outName,
        'psnr'       => round($psnr, 2),
        'mse'        => round($mse, 6),
        'msg_length' => strlen($message),
        'capacity'   => $capacity,
    ]);
    exit;
}

// ── DECODE ────────────────────────────────────────────────
if ($action === 'decode') {
    if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(['success' => false, 'message' => 'Gagal upload gambar.']);
        exit;
    }

    $key = $_POST['key'] ?? '';
    $img = imagecreatefrompng($_FILES['image']['tmp_name']);
    if (!$img) {
        echo json_encode(['success' => false, 'message' => 'Gagal membaca gambar.']);
        exit;
    }

    $width  = imagesx($img);
    $height = imagesy($img);

    // Ekstrak bit LSB
    $bits = '';
    for ($y = 0; $y < $height; $y++) {
        for ($x = 0; $x < $width; $x++) {
            $rgb = imagecolorat($img, $x, $y);
            $bits .= ($rgb >> 16) & 1;
            $bits .= ($rgb >> 8)  & 1;
            $bits .=  $rgb        & 1;
        }
    }
    imagedestroy($img);

    // Baca 32 bit pertama sebagai panjang pesan
    $lenBits = substr($bits, 0, 32);
    $msgLen  = bindec($lenBits);

    if ($msgLen <= 0 || $msgLen > 100000) {
        echo json_encode(['success' => false, 'message' => 'Tidak ditemukan pesan tersembunyi atau gambar rusak.']);
        exit;
    }

    // Ekstrak pesan
    $msgBits = substr($bits, 32, $msgLen * 8);
    $message = '';
    for ($i = 0; $i < $msgLen * 8; $i += 8) {
        $message .= chr(bindec(substr($msgBits, $i, 8)));
    }

    // Dekripsi XOR jika ada kunci
    if ($key) $message = xorEncrypt($message, $key);

    // Simpan riwayat jika login
    if (isLoggedIn()) {
        $db   = getDB();
        $stmt = $db->prepare("INSERT INTO history (user_id, type, original_file, msg_length) VALUES (?,?,?,?)");
        $stmt->execute([$_SESSION['user_id'], 'decode', $_FILES['image']['name'], $msgLen]);
    }

    echo json_encode([
        'success'    => true,
        'message'    => $message,
        'msg_length' => $msgLen,
    ]);
    exit;
}

// ── HELPER FUNCTIONS ──────────────────────────────────────

function toBinary($num, $bits = 8) {
    return str_pad(decbin($num), $bits, '0', STR_PAD_LEFT);
}

function strToBinary($str) {
    $bin = '';
    for ($i = 0; $i < strlen($str); $i++) {
        $bin .= toBinary(ord($str[$i]));
    }
    return $bin;
}

function xorEncrypt($text, $key) {
    $out    = '';
    $keyLen = strlen($key);
    for ($i = 0; $i < strlen($text); $i++) {
        $out .= chr(ord($text[$i]) ^ ord($key[$i % $keyLen]));
    }
    return $out;
}

function calcPSNR($orig, $stego, $width, $height) {
    $mse = 0;
    for ($y = 0; $y < $height; $y++) {
        for ($x = 0; $x < $width; $x++) {
            $o = imagecolorat($orig,  $x, $y);
            $s = imagecolorat($stego, $x, $y);
            foreach ([16, 8, 0] as $shift) {
                $diff = (($o >> $shift) & 0xFF) - (($s >> $shift) & 0xFF);
                $mse += $diff * $diff;
            }
        }
    }
    $mse /= ($width * $height * 3);
    $psnr = ($mse == 0) ? 100 : 10 * log10((255 * 255) / $mse);
    return [$psnr, $mse];
}
?>
