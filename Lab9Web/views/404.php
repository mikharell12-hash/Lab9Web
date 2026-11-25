<?php
/**
 * views/404.php
 * Halaman kesalahan 404 ketika nilai parameter page tidak ditemukan pada whitelist router.
 */
$pageTitle = '404 - Halaman Tidak Ditemukan';
$requestedRaw = isset($_GET['page']) && is_string($_GET['page']) ? $_GET['page'] : '(parameter tidak valid)';
?>

<section class="panel error-404-box">
    <span class="badge badge-red">HTTP 404 Not Found</span>
    <h2>404 - Halaman Tidak Ditemukan</h2>
    <p>Rute <code><?= e($requestedRaw); ?></code> tidak terdaftar pada daftar <em>whitelist</em> router aplikasi atau format parameter tidak sesuai.</p>
    <p class="text-muted">Mekanisme whitelist mencegah pemanggilan path file sembarangan (<em>path traversal</em> / <em>Local File Inclusion</em>).</p>
    <div class="hero-actions" style="justify-content: center; margin-top: 18px;">
        <a href="<?= e(routeUrl('dashboard')); ?>" class="btn btn-primary">Kembali ke Dashboard</a>
        <a href="<?= e(routeUrl('barang/list')); ?>" class="btn btn-secondary">Lihat Data Barang</a>
    </div>
</section>
