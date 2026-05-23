-- ============================================
-- StegoWeb - Database Schema
-- Jalankan file ini di phpMyAdmin atau MySQL CLI
-- ============================================

CREATE DATABASE IF NOT EXISTS stegoweb CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE stegoweb;

-- Tabel pengguna
CREATE TABLE IF NOT EXISTS users (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    username    VARCHAR(50)  NOT NULL UNIQUE,
    email       VARCHAR(100) NOT NULL UNIQUE,
    password    VARCHAR(255) NOT NULL,          -- bcrypt hash
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel riwayat encode/decode (hanya member)
CREATE TABLE IF NOT EXISTS history (
    id             INT AUTO_INCREMENT PRIMARY KEY,
    user_id        INT NOT NULL,
    type           ENUM('encode','decode') NOT NULL,
    original_file  VARCHAR(255),               -- nama file asli
    stego_file     VARCHAR(255),               -- nama file hasil stego
    msg_length     INT DEFAULT 0,              -- panjang pesan (karakter)
    psnr_value     FLOAT DEFAULT NULL,         -- nilai PSNR (dB)
    mse_value      FLOAT DEFAULT NULL,
    ssim_value     FLOAT DEFAULT NULL,
    created_at     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Index untuk query riwayat
CREATE INDEX idx_history_user ON history(user_id);
CREATE INDEX idx_history_type ON history(type);
