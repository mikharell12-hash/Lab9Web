-- ============================================================================
-- Database Praktikum 9 - PHP Modular (Integrasi CRUD Praktikum 8 & Modul User)
-- Nama  : Alleizandro Lim
-- NIM   : 312510395
-- Kelas : I252A
-- ============================================================================

CREATE DATABASE IF NOT EXISTS latihan1
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE latihan1;

-- Tabel data_barang (lanjutan dari Praktikum 8 dengan kolom deskripsi tambahan)
DROP TABLE IF EXISTS data_barang;
CREATE TABLE data_barang (
    id_barang INT AUTO_INCREMENT PRIMARY KEY,
    kategori VARCHAR(50) NOT NULL,
    nama VARCHAR(100) NOT NULL,
    deskripsi TEXT DEFAULT NULL,
    gambar VARCHAR(100) DEFAULT 'default-item.svg',
    harga_beli DECIMAL(12, 0) NOT NULL DEFAULT 0,
    harga_jual DECIMAL(12, 0) NOT NULL DEFAULT 0,
    stok INT(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO data_barang (kategori, nama, deskripsi, gambar, harga_beli, harga_jual, stok) VALUES
('Elektronik', 'HP Samsung Android', 'Smartphone layar Super AMOLED 6.5 inci dengan baterai 5000mAh dan fitur NFC untuk kebutuhan produktivitas harian.', 'hp-samsung.svg', 2000000, 2400000, 8),
('Elektronik', 'HP Xiaomi Android', 'Ponsel kelas menengah dengan prosesor octa-core hemat daya, kamera utama 50MP, dan pengisian daya cepat 33W.', 'hp-xiaomi.svg', 1500000, 1850000, 12),
('Elektronik', 'HP OPPO Android', 'Desain tipis ergonomis dengan RAM 8GB, penyimpanan internal 256GB, serta perlindungan cipratan air IP54.', 'hp-oppo.svg', 1800000, 2300000, 6),
('Komputer', 'Laptop ASUS VivoBook 14', 'Laptop tipis bobot ringan untuk pemrograman web, dilengkapi SSD NVMe 512GB dan layar IPS Full HD anti-glare.', 'laptop-asus.svg', 6800000, 7650000, 4),
('Aksesoris', 'Keyboard Mekanik RGB', 'Keyboard mekanik tactile switch 75% layout dengan kabel braided USB Type-C yang nyaman untuk mengetik kode.', 'keyboard-rgb.svg', 350000, 480000, 15);

-- Tabel users (untuk modul user/ dan autentikasi auth/ sesuai struktur Bagian F)
DROP TABLE IF EXISTS users;
CREATE TABLE users (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(30) NOT NULL DEFAULT 'Operator',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Password bawaan seluruh akun contoh: admin123
INSERT INTO users (nama, email, password, role) VALUES
('Alleizandro Lim', 'admin@kampus.ac.id', '$2y$12$o.YAkIFoqA3z4.FPbHttd.Vwe8sZoVE58FMLnuFHOvyqoru8CisW.', 'Admin'),
('Rina Kusuma', 'rina.gudang@kampus.ac.id', '$2y$12$o.YAkIFoqA3z4.FPbHttd.Vwe8sZoVE58FMLnuFHOvyqoru8CisW.', 'Staff Gudang'),
('Bima Pratama', 'bima.kasir@kampus.ac.id', '$2y$12$o.YAkIFoqA3z4.FPbHttd.Vwe8sZoVE58FMLnuFHOvyqoru8CisW.', 'Kasir');
