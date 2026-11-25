<?php
/**
 * modules/barang/list.php
 * Modul Read Data Barang (Integrasi CRUD Praktikum 8 dengan Routing & Helper).
 */
$pageTitle = 'Data Barang (Inventaris CRUD)';

$kataKunci = isset($_GET['q']) && is_string($_GET['q']) ? trim($_GET['q']) : '';
$kategoriFilter = isset($_GET['kategori']) && is_string($_GET['kategori']) ? trim($_GET['kategori']) : '';

$sql = "SELECT * FROM data_barang WHERE 1=1";
$params = [];

if ($kataKunci !== '') {
    $sql .= " AND (nama LIKE :q1 OR deskripsi LIKE :q2)";
    $params[':q1'] = '%' . $kataKunci . '%';
    $params[':q2'] = '%' . $kataKunci . '%';
}

if ($kategoriFilter !== '') {
    $sql .= " AND kategori = :kategori";
    $params[':kategori'] = $kategoriFilter;
}

$sql .= " ORDER BY id_barang DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$daftarBarang = $stmt->fetchAll();

$daftarKategori = ['Elektronik', 'Komputer', 'Aksesoris', 'Jaringan', 'Lainnya'];
?>

<section class="panel">
    <div class="panel-header">
        <div>
            <h2>Manajemen Data Barang</h2>
            <p class="panel-subtitle">Hasil refactoring aplikasi CRUD Praktikum 8 ke dalam modul <code>modules/barang/</code> melalui router <code>index.php</code>.</p>
        </div>
        <a href="<?= e(routeUrl('barang/add')); ?>" class="btn btn-primary">+ Tambah Barang</a>
    </div>

    <form method="get" action="<?= e(baseUrl('index.php')); ?>" class="filter-bar">
        <input type="hidden" name="page" value="barang/list">
        <input type="text" name="q" value="<?= e($kataKunci); ?>" placeholder="Cari nama atau deskripsi barang...">
        <select name="kategori">
            <option value="">Semua Kategori</option>
            <?php foreach ($daftarKategori as $kat): ?>
                <option value="<?= e($kat); ?>" <?= $kategoriFilter === $kat ? 'selected' : ''; ?>><?= e($kat); ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit" class="btn btn-secondary">Filter</button>
        <?php if ($kataKunci !== '' || $kategoriFilter !== ''): ?>
            <a href="<?= e(routeUrl('barang/list')); ?>" class="btn btn-link">Reset</a>
        <?php endif; ?>
    </form>

    <?php if (!isLoggedIn()): ?>
        <div class="info-strip">
            Anda sedang melihat dalam <strong>Mode Tamu</strong>. Untuk menambah, mengubah, atau menghapus data barang, silakan <a href="<?= e(routeUrl('auth/login')); ?>">Login Admin</a> terlebih dahulu.
        </div>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama &amp; Ringkasan Barang</th>
                    <th>Kategori</th>
                    <th>Harga Beli</th>
                    <th>Harga Jual</th>
                    <th>Stok</th>
                    <th style="width: 170px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($daftarBarang)): ?>
                    <tr>
                        <td colspan="7" class="empty-cell">Tidak ada data barang yang sesuai kriteria pencarian.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($daftarBarang as $row): ?>
                        <tr>
                            <td>#<?= (int)$row['id_barang']; ?></td>
                            <td>
                                <strong><?= e($row['nama']); ?></strong>
                                <div class="text-muted"><?= e(ringkasTeks((string)($row['deskripsi'] ?? '-'), 60)); ?></div>
                            </td>
                            <td><span class="badge badge-blue"><?= e($row['kategori']); ?></span></td>
                            <td><?= e(formatRupiah((float)$row['harga_beli'])); ?></td>
                            <td><strong><?= e(formatRupiah((float)$row['harga_jual'])); ?></strong></td>
                            <td>
                                <span class="badge <?= (int)$row['stok'] <= 5 ? 'badge-amber' : 'badge-green'; ?>">
                                    <?= (int)$row['stok']; ?> unit
                                </span>
                            </td>
                            <td>
                                <div class="action-group">
                                    <a href="<?= e(routeUrl('barang/edit', ['id' => (int)$row['id_barang']])); ?>" class="btn btn-sm btn-secondary">Ubah</a>
                                    <form method="post" action="<?= e(routeUrl('barang/delete')); ?>" class="inline-form js-confirm-delete" data-nama="<?= e($row['nama']); ?>">
                                        <?= csrfField(); ?>
                                        <input type="hidden" name="id_barang" value="<?= (int)$row['id_barang']; ?>">
                                        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>
