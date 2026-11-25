<?php
/**
 * modules/barang/add.php
 * Modul Create Data Barang dengan validasi server, CSRF, dan pembatasan login.
 */
requireLogin('barang/add');
$pageTitle = 'Tambah Data Barang';

$daftarKategori = ['Elektronik', 'Komputer', 'Aksesoris', 'Jaringan', 'Lainnya'];
$errors = [];
$input = [
    'nama'       => '',
    'kategori'   => 'Elektronik',
    'harga_beli' => '',
    'harga_jual' => '',
    'stok'       => '',
    'deskripsi'  => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? null)) {
        $errors[] = 'Token keamanan CSRF tidak valid. Silakan kirim ulang formulir.';
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
        if (is_numeric($input['harga_beli']) && is_numeric($input['harga_jual']) && (float)$input['harga_jual'] < (float)$input['harga_beli']) {
            $errors[] = 'Harga jual sebaiknya tidak lebih rendah dari harga beli.';
        }
        if (filter_var($input['stok'], FILTER_VALIDATE_INT) === false || (int)$input['stok'] < 0) {
            $errors[] = 'Stok harus berupa bilangan bulat minimal 0.';
        }

        $namaGambar = 'default-item.svg';
        if (!empty($_FILES['gambar']['name']) && $_FILES['gambar']['error'] !== UPLOAD_ERR_NO_FILE) {
            if ($_FILES['gambar']['error'] !== UPLOAD_ERR_OK) {
                $errors[] = 'Gagal mengunggah file gambar.';
            } else {
                $ext = strtolower(pathinfo((string)$_FILES['gambar']['name'], PATHINFO_EXTENSION));
                $allowedExt = ['jpg', 'jpeg', 'png', 'webp', 'svg'];
                if (!in_array($ext, $allowedExt, true)) {
                    $errors[] = 'Format gambar harus JPG, PNG, WEBP, atau SVG.';
                } elseif ((int)$_FILES['gambar']['size'] > 2 * 1024 * 1024) {
                    $errors[] = 'Ukuran gambar maksimal 2 MB.';
                } else {
                    $uploadDir = dirname(__DIR__, 2) . '/assets/img';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }
                    $namaGambar = 'barang-' . time() . '-' . bin2hex(random_bytes(3)) . '.' . $ext;
                    if (!move_uploaded_file($_FILES['gambar']['tmp_name'], $uploadDir . '/' . $namaGambar)) {
                        $errors[] = 'Tidak dapat menyimpan file gambar ke direktori assets/img.';
                    }
                }
            }
        }

        if (empty($errors)) {
            $stmt = $pdo->prepare("
                INSERT INTO data_barang (kategori, nama, deskripsi, gambar, harga_beli, harga_jual, stok)
                VALUES (:kategori, :nama, :deskripsi, :gambar, :harga_beli, :harga_jual, :stok)
            ");
            $stmt->execute([
                ':kategori'   => $input['kategori'],
                ':nama'       => $input['nama'],
                ':deskripsi'  => $input['deskripsi'] !== '' ? $input['deskripsi'] : 'Barang inventaris kategori ' . $input['kategori'],
                ':gambar'     => $namaGambar,
                ':harga_beli' => (float)$input['harga_beli'],
                ':harga_jual' => (float)$input['harga_jual'],
                ':stok'       => (int)$input['stok'],
            ]);

            setFlashMessage('success', 'Data barang "' . $input['nama'] . '" berhasil ditambahkan ke inventaris.');
            header('Location: ' . routeUrl('barang/list'));
            exit;
        }
    }
}
?>

<section class="panel">
    <div class="panel-header">
        <div>
            <h2>Tambah Barang Baru</h2>
            <p class="panel-subtitle">Isi formulir berikut untuk menambahkan stok barang ke tabel <code>data_barang</code>.</p>
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

    <form method="post" action="<?= e(routeUrl('barang/add')); ?>" enctype="multipart/form-data" class="form-layout">
        <?= csrfField(); ?>
        <div class="form-row">
            <div class="form-field">
                <label for="nama">Nama Barang</label>
                <input type="text" id="nama" name="nama" value="<?= e($input['nama']); ?>" placeholder="Contoh: Monitor IPS 24 Inci" required>
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
                <input type="number" id="harga_beli" name="harga_beli" value="<?= e($input['harga_beli']); ?>" min="0" placeholder="1450000" required>
            </div>
            <div class="form-field">
                <label for="harga_jual">Harga Jual (Rp)</label>
                <input type="number" id="harga_jual" name="harga_jual" value="<?= e($input['harga_jual']); ?>" min="1" placeholder="1750000" required>
            </div>
            <div class="form-field">
                <label for="stok">Jumlah Stok</label>
                <input type="number" id="stok" name="stok" value="<?= e($input['stok']); ?>" min="0" placeholder="10" required>
            </div>
        </div>

        <div class="form-field">
            <label for="deskripsi">Deskripsi Barang</label>
            <textarea id="deskripsi" name="deskripsi" rows="3" placeholder="Spesifikasi singkat barang (akan diringkas otomatis pada tabel daftar)..."><?= e($input['deskripsi']); ?></textarea>
        </div>

        <div class="form-field">
            <label for="gambar">File Gambar (Opsional - JPG/PNG/WEBP/SVG, maks. 2 MB)</label>
            <input type="file" id="gambar" name="gambar" accept=".jpg,.jpeg,.png,.webp,.svg">
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Simpan Barang</button>
            <a href="<?= e(routeUrl('barang/list')); ?>" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</section>
