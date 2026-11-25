<?php
/**
 * config/database.php
 * Konfigurasi koneksi database tunggal menggunakan PDO (Bagian I & halaman 9).
 */

$host = 'localhost';
$db   = 'latihan1';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO(
        "mysql:host={$host};dbname={$db};charset=utf8mb4",
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {
    // Detail teknis dicatat ke log server, tidak ditampilkan mentah ke pengguna
    error_log('Database Connection Error: ' . $e->getMessage());
    http_response_code(500);
    exit('Terjadi kesalahan pada koneksi database. Pastikan layanan MySQL aktif dan database latihan1 telah diimpor.');
}
