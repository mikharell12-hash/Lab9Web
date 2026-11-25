<?php
/**
 * views/dashboard.php
 * Halaman beranda ringkasan data inventaris barang, pengguna, dan fitur modular.
 */
$pageTitle = 'Dashboard Aplikasi Modular';

$statBarang = $pdo->query("
    SELECT
        COUNT(*) AS total_jenis,
        COALESCE(SUM(stok), 0) AS total_stok,
        COALESCE(SUM(harga_jual * stok), 0) AS nilai_persediaan
    FROM data_barang
")->fetch();

$totalUser = (int)$pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();

$barangTerbaru = $pdo->query("
    SELECT id_barang, kategori, nama, deskripsi, harga_jual, stok
    FROM data_barang
    ORDER BY id_barang DESC
    LIMIT 4
")->fetchAll();
?>

<section class="hero-banner">
    <div>
        <span class="eyebrow">MODUL PRAKTIKUM 9 &bull; PHP MODULAR</span>
        <h1>Sistem Inventaris &amp; Manajemen Pengguna Terintegrasi</h1>
        <p>Seluruh halaman dikendalikan satu pintu melalui router <code>index.php</code> dengan whitelist rute, komponen layout bersama, pustaka helper function, serta proteksi CSRF dan autentikasi sesi.</p>
    </div>
    <div class="hero-actions">
        <a href="<?= e(routeUrl('barang/list')); ?>" class="btn btn-primary">Kelola Data Barang</a>
        <a href="<?= e(routeUrl('function/demo')); ?>" class="btn btn-secondary">Uji Library Function</a>
    </div>
</section>

<section class="stats-grid">
    <article class="stat-card">
        <span class="stat-label">Jenis Barang</span>
        <strong class="stat-value"><?= (int)$statBarang['total_jenis']; ?> Item</strong>
        <span class="stat-sub">Tabel <code>data_barang</code></span>
    </article>
    <article class="stat-card">
        <span class="stat-label">Total Unit Stok</span>
        <strong class="stat-value"><?= (int)$statBarang['total_stok']; ?> Unit</strong>
        <span class="stat-sub">Akumulasi stok tersedia</span>
    </article>
    <article class="stat-card">
        <span class="stat-label">Estimasi Nilai Jual</span>
        <strong class="stat-value"><?= e(formatRupiah((float)$statBarang['nilai_persediaan'])); ?></strong>
        <span class="stat-sub">Diformat dengan <code>formatRupiah()</code></span>
    </article>
    <article class="stat-card">
        <span class="stat-label">Pengguna Terdaftar</span>
        <strong class="stat-value"><?= $totalUser; ?> Akun</strong>
        <span class="stat-sub">Modul <code>modules/user/</code></span>
    </article>
</section>

<section class="panel">
    <div class="panel-header">
        <div>
            <h2>Ringkasan Inventaris Terbaru</h2>
            <p class="panel-subtitle">Menampilkan data dari database dengan fungsi <code>formatRupiah()</code>, <code>hitungDiskon()</code>, dan <code>ringkasTeks()</code>.</p>
        </div>
        <a href="<?= e(routeUrl('barang/add')); ?>" class="btn btn-primary btn-sm">+ Tambah Barang</a>
    </div>

    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Barang &amp; Ringkasan</th>
                    <th>Kategori</th>
                    <th>Harga Normal</th>
                    <th>Harga Promo (Diskon 10%)</th>
                    <th>Stok</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($barangTerbaru)): ?>
                    <tr>
                        <td colspan="5" class="empty-cell">Belum ada data barang pada database.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($barangTerbaru as $item):
                        $hargaJual = (float)$item['harga_jual'];
                        $hargaPromo = $hargaJual - hitungDiskon($hargaJual, 10);
                    ?>
                        <tr>
                            <td>
                                <strong><?= e($item['nama']); ?></strong>
                                <div class="text-muted"><?= e(ringkasTeks((string)($item['deskripsi'] ?? ''), 62)); ?></div>
                            </td>
                            <td><span class="badge badge-blue"><?= e($item['kategori']); ?></span></td>
                            <td><?= e(formatRupiah($hargaJual)); ?></td>
                            <td class="text-success"><strong><?= e(formatRupiah($hargaPromo)); ?></strong></td>
                            <td><span class="badge badge-neutral"><?= (int)$item['stok']; ?> unit</span></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>
