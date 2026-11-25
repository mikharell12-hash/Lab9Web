<?php
/**
 * modules/user/add.php
 * Modul tambah pengguna baru menggunakan validasiEmail() dan password_hash().
 */
requireLogin('user/add');
$pageTitle = 'Tambah Pengguna Baru';

$daftarRole = ['Admin', 'Staff Gudang', 'Kasir', 'Operator'];
$errors = [];
$input = [
    'nama'  => '',
    'email' => '',
    'role'  => 'Operator',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? null)) {
        $errors[] = 'Token keamanan CSRF tidak valid.';
    } else {
        $input['nama']  = trim((string)($_POST['nama'] ?? ''));
        $input['email'] = strtolower(trim((string)($_POST['email'] ?? '')));
        $input['role']  = trim((string)($_POST['role'] ?? 'Operator'));
        $passwordPlain  = (string)($_POST['password'] ?? '');

        if ($input['nama'] === '') {
            $errors[] = 'Nama lengkap pengguna wajib diisi.';
        }
        if (!validasiEmail($input['email'])) {
            $errors[] = 'Format email tidak valid.';
        }
        if (!in_array($input['role'], $daftarRole, true)) {
            $errors[] = 'Peran pengguna tidak dikenali.';
        }
        if (strlen($passwordPlain) < 6) {
            $errors[] = 'Kata sandi minimal 6 karakter.';
        }

        if (empty($errors)) {
            $cekEmail = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
            $cekEmail->execute([':email' => $input['email']]);
            if ((int)$cekEmail->fetchColumn() > 0) {
                $errors[] = 'Email tersebut sudah terdaftar pada pengguna lain.';
            }
        }

        if (empty($errors)) {
            $stmtSimpan = $pdo->prepare("
                INSERT INTO users (nama, email, password, role)
                VALUES (:nama, :email, :password, :role)
            ");
            $stmtSimpan->execute([
                ':nama'     => $input['nama'],
                ':email'    => $input['email'],
                ':password' => password_hash($passwordPlain, PASSWORD_BCRYPT),
                ':role'     => $input['role'],
            ]);

            setFlashMessage('success', 'Pengguna "' . $input['nama'] . '" berhasil ditambahkan.');
            header('Location: ' . routeUrl('user/list'));
            exit;
        }
    }
}
?>

<section class="panel">
    <div class="panel-header">
        <div>
            <h2>Tambah Pengguna</h2>
            <p class="panel-subtitle">Menambahkan akun baru dengan validasi fungsi <code>validasiEmail()</code>.</p>
        </div>
        <a href="<?= e(routeUrl('user/list')); ?>" class="btn btn-secondary">&larr; Kembali ke Daftar</a>
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

    <form method="post" action="<?= e(routeUrl('user/add')); ?>" class="form-layout">
        <?= csrfField(); ?>
        <div class="form-row">
            <div class="form-field">
                <label for="nama">Nama Lengkap</label>
                <input type="text" id="nama" name="nama" value="<?= e($input['nama']); ?>" placeholder="Contoh: Citra Dewi" required>
            </div>
            <div class="form-field">
                <label for="email">Alamat Email</label>
                <input type="email" id="email" name="email" value="<?= e($input['email']); ?>" placeholder="citra@kampus.ac.id" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-field">
                <label for="role">Peran (Role)</label>
                <select id="role" name="role">
                    <?php foreach ($daftarRole as $r): ?>
                        <option value="<?= e($r); ?>" <?= $input['role'] === $r ? 'selected' : ''; ?>><?= e($r); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-field">
                <label for="password">Kata Sandi Awal</label>
                <input type="password" id="password" name="password" placeholder="Minimal 6 karakter" required>
            </div>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Simpan Pengguna</button>
            <a href="<?= e(routeUrl('user/list')); ?>" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</section>
