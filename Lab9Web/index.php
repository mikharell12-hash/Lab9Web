<?php
/**
 * index.php
 * Titik masuk utama aplikasi (Front Controller / Router dengan Whitelist)
 * Praktikum 9: PHP Modular - Alleizandro Lim (312510395 - I252A)
 */

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/helpers/functions.php';

// Daftar rute yang diizinkan (Whitelist Routing - Bagian G, I, & Latihan 3)
$routes = [
    // Halaman umum & latihan template/function
    'dashboard'     => __DIR__ . '/views/dashboard.php',
    'profil'        => __DIR__ . '/views/profil.php',
    'kontak'        => __DIR__ . '/views/kontak.php',
    'function/demo' => __DIR__ . '/views/demo_function.php',

    // Modul CRUD Data Barang (Integrasi Praktikum 8 - Latihan 4)
    'barang/list'   => __DIR__ . '/modules/barang/list.php',
    'barang/add'    => __DIR__ . '/modules/barang/add.php',
    'barang/edit'   => __DIR__ . '/modules/barang/edit.php',
    'barang/delete' => __DIR__ . '/modules/barang/delete.php',

    // Modul CRUD Data Pengguna (Struktur Bagian F & G)
    'user/list'     => __DIR__ . '/modules/user/list.php',
    'user/add'      => __DIR__ . '/modules/user/add.php',
    'user/edit'     => __DIR__ . '/modules/user/edit.php',
    'user/delete'   => __DIR__ . '/modules/user/delete.php',

    // Modul Autentikasi (Struktur Bagian F & Tantangan Mandiri)
    'auth/login'    => __DIR__ . '/modules/auth/login.php',
    'auth/logout'   => __DIR__ . '/modules/auth/logout.php',
];

// Membaca dan menormalisasi parameter page secara aman
$rawPage = $_GET['page'] ?? 'dashboard';

if (!is_string($rawPage)) {
    // Menangani parameter bertipe tidak valid (misalnya ?page[]=uji)
    http_response_code(404);
    $currentPage = '404';
    $contentFile = __DIR__ . '/views/404.php';
} else {
    $normalizedPage = trim($rawPage, "/ \t\n\r\0\x0B");
    if ($normalizedPage === '') {
        $normalizedPage = 'dashboard';
    }

    if (!array_key_exists($normalizedPage, $routes) || !is_file($routes[$normalizedPage])) {
        http_response_code(404);
        $currentPage = '404';
        $contentFile = __DIR__ . '/views/404.php';
    } else {
        $currentPage = $normalizedPage;
        $contentFile = $routes[$normalizedPage];
    }
}

$pageTitle = 'Aplikasi Inventaris & Pengguna Modular';

// Menjalankan modul konten di dalam output buffer agar redirect header('Location: ...')
// pada operasi POST/autentikasi tetap bersih tanpa error "headers already sent".
ob_start();
require $contentFile;
$pageContent = ob_get_clean();

// Merender layout utama satu pintu (header -> konten -> footer)
require_once __DIR__ . '/views/header.php';
echo $pageContent;
require_once __DIR__ . '/views/footer.php';
