<?php
/**
 * modules/barang/edit.php
 * Modul Update Data Barang dengan validasi server, CSRF, dan pembatasan login.
 */
requireLogin('barang/list');
$pageTitle = 'Ubah Data Barang';

$id = isset($_GET['id']) && filter_var($_GET['id'], FILTER_VALIDATE_INT) !== false
    ? (int)$_GET['id']
    : (isset($_POST['id_barang']) ? (int)$_POST['id_barang'] : 0);

$stmtCari = $pdo->prepare("SELECT * FROM data_barang WHERE id_barang = :id");
$stmtCari->execute([':id' => $id]);
$barang = $stmtCari->fetch();

if (!$barang) {
    setFlashMessage('error', 'Data barang dengan ID tersebut tidak ditemukan.');
    header('Location: ' . routeUrl('barang/list'));
    exit;
}

$daftarKategori = ['Elektronik', 'Komputer', 'Aksesoris', 'Jaringan', 'Lainnya'];
$errors = [];
$input = [
    'nama'       => (string)$barang['nama'],
    'kategori'   => (string)$barang['kategori'],
    'harga_beli' => (string)$barang['harga_beli'],
    'harga_jual' => (string)$barang['harga_jual'],
    'stok'       => (string)$barang['stok'],
    'deskripsi'  => (string)($barang['deskripsi'] ?? ''),
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? null)) {
        $errors[] = 'Token keamanan CSRF tidak valid.';
    } else {
        $input['nama']       = trim((string)($_POST['nama'] ?? ''));
        $input['kategori']   = trim((string)($_POST['kategori'] ?? ''));
        $input['harga_beli'] = trim((string)($_POST['harga_beli'] ?? ''));
        $input['harga_jual'] = trim((string)($_POST['harga_jual'] ?? ''));
        $input['stok']       = trim((string)($_POST['stok'] ?? ''));
        $input['deskripsi']  = trim((string)($_POST['deskripsi'] ?? ''));

        if ($input['nama'] === '') {
            $errors[] = 'Nama barang wajib diisi.';
        }
        if (!in_array($input['kategori'], $daftarKategori, true)) {
            $errors[] = 'Kategori barang tidak valid.';
        }
        if (!is_numeric($input['harga_beli']) || (float)$input['harga_beli'] < 0) {
            $errors[] = 'Harga beli harus berupa angka positif.';
        }
        if (!is_numeric($input['harga_jual']) || (float)$input['harga_jual'] <= 0) {
            $errors[] = 'Harga jual harus berupa angka lebih dari 0.';
        }
        if (filter_var($input['stok'], FILTER_VALIDATE_INT) === false || (int)$input['stok'] < 0) {
            $errors[] = 'Stok harus berupa bilangan bulat minimal 0.';
        }

        if (empty($errors)) {
            $stmtUpdate = $pdo->prepare("
                UPDATE data_barang
                SET kategori = :kategori,
                    nama = :nama,
                    deskripsi = :deskripsi,
                    harga_beli = :harga_beli,
                    harga_jual = :harga_jual,
                    stok = :stok
                WHERE id_barang = :id
            ");
            $stmtUpdate->execute([
                ':kategori'   => $input['kategori'],
                ':nama'       => $input['nama'],
                ':deskripsi'  => $input['deskripsi'],
                ':harga_beli' => (float)$input['harga_beli'],
                ':harga_jual' => (float)$input['harga_jual'],
                ':stok'       => (int)$input['stok'],
                ':id'         => $id,
            ]);

            setFlashMessage('success', 'Perubahan pada barang "' . $input['nama'] . '" berhasil disimpan.');
            header('Location: ' . routeUrl('barang/list'));
            exit;
        }
    }
}
?>

<section class="panel">
    <div class="panel-header">
        <div>
            <h2>Ubah Data Barang #<?= $id; ?></h2>
            <p class="panel-subtitle">Perbarui informasi inventaris lalu klik Simpan Perubahan.</p>
        </div>
        <a href="<?= e(routeUrl('barang/list')); ?>" class="btn btn-secondary">&larr; Kembali ke Daftar</a>
    </div>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-error">
            <strong>Validasi gagal:</strong>
            <ul class="error-list">
                <?php foreach ($errors as $err): ?>
                    <li><?= e($err); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="post" action="<?= e(routeUrl('barang/edit', ['id' => $id])); ?>" class="form-layout">
        <?= csrfField(); ?>
        <input type="hidden" name="id_barang" value="<?= $id; ?>">

        <div class="form-row">
            <div class="form-field">
                <label for="nama">Nama Barang</label>
                <input type="text" id="nama" name="nama" value="<?= e($input['nama']); ?>" required>
            </div>
            <div class="form-field">
                <label for="kategori">Kategori</label>
                <select id="kategori" name="kategori" required>
                    <?php foreach ($daftarKategori as $kat): ?>
                        <option value="<?= e($kat); ?>" <?= $input['kategori'] === $kat ? 'selected' : ''; ?>><?= e($kat); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-field">
                <label for="harga_beli">Harga Beli (Rp)</label>
                <input type="number" id="harga_beli" name="harga_beli" value="<?= e($input['harga_beli']); ?>" min="0" required>
            </div>
            <div class="form-field">
                <label for="harga_jual">Harga Jual (Rp)</label>
                <input type="number" id="harga_jual" name="harga_jual" value="<?= e($input['harga_jual']); ?>" min="1" required>
            </div>
            <div class="form-field">
                <label for="stok">Jumlah Stok</label>
                <input type="number" id="stok" name="stok" value="<?= e($input['stok']); ?>" min="0" required>
            </div>
        </div>

        <div class="form-field">
            <label for="deskripsi">Deskripsi Barang</label>
            <textarea id="deskripsi" name="deskripsi" rows="3"><?= e($input['deskripsi']); ?></textarea>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <a href="<?= e(routeUrl('barang/list')); ?>" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</section>
