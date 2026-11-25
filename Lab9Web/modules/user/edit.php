<?php
/**
 * modules/user/edit.php
 * Modul pembaruan data pengguna.
 */
requireLogin('user/list');
$pageTitle = 'Ubah Data Pengguna';

$id = isset($_GET['id']) && filter_var($_GET['id'], FILTER_VALIDATE_INT) !== false
    ? (int)$_GET['id']
    : (isset($_POST['id_user']) ? (int)$_POST['id_user'] : 0);

$stmtCari = $pdo->prepare("SELECT * FROM users WHERE id_user = :id");
$stmtCari->execute([':id' => $id]);
$dataUser = $stmtCari->fetch();

if (!$dataUser) {
    setFlashMessage('error', 'Data pengguna tidak ditemukan.');
    header('Location: ' . routeUrl('user/list'));
    exit;
}

$daftarRole = ['Admin', 'Staff Gudang', 'Kasir', 'Operator'];
$errors = [];
$input = [
    'nama'  => (string)$dataUser['nama'],
    'email' => (string)$dataUser['email'],
    'role'  => (string)$dataUser['role'],
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? null)) {
        $errors[] = 'Token keamanan CSRF tidak valid.';
    } else {
        $input['nama']  = trim((string)($_POST['nama'] ?? ''));
        $input['email'] = strtolower(trim((string)($_POST['email'] ?? '')));
        $input['role']  = trim((string)($_POST['role'] ?? 'Operator'));
        $passwordBaru   = (string)($_POST['password'] ?? '');

        if ($input['nama'] === '') {
            $errors[] = 'Nama pengguna wajib diisi.';
        }
        if (!validasiEmail($input['email'])) {
            $errors[] = 'Format email tidak valid.';
        }
        if (!in_array($input['role'], $daftarRole, true)) {
            $errors[] = 'Peran pengguna tidak valid.';
        }
        if ($passwordBaru !== '' && strlen($passwordBaru) < 6) {
            $errors[] = 'Kata sandi baru minimal 6 karakter.';
        }

        if (empty($errors)) {
            $cekEmail = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = :email AND id_user <> :id");
            $cekEmail->execute([':email' => $input['email'], ':id' => $id]);
            if ((int)$cekEmail->fetchColumn() > 0) {
                $errors[] = 'Email tersebut sudah dipakai akun lain.';
            }
        }

        if (empty($errors)) {
            if ($passwordBaru !== '') {
                $stmtUpd = $pdo->prepare("
                    UPDATE users
                    SET nama = :nama, email = :email, role = :role, password = :password
                    WHERE id_user = :id
                ");
                $stmtUpd->execute([
                    ':nama'     => $input['nama'],
                    ':email'    => $input['email'],
                    ':role'     => $input['role'],
                    ':password' => password_hash($passwordBaru, PASSWORD_BCRYPT),
                    ':id'       => $id,
                ]);
            } else {
                $stmtUpd = $pdo->prepare("
                    UPDATE users
                    SET nama = :nama, email = :email, role = :role
                    WHERE id_user = :id
                ");
                $stmtUpd->execute([
                    ':nama'  => $input['nama'],
                    ':email' => $input['email'],
                    ':role'  => $input['role'],
                    ':id'    => $id,
                ]);
            }

            if ((int)(currentUser()['id_user'] ?? 0) === $id) {
                $_SESSION['auth_user']['nama']  = $input['nama'];
                $_SESSION['auth_user']['email'] = $input['email'];
                $_SESSION['auth_user']['role']  = $input['role'];
            }

            setFlashMessage('success', 'Data pengguna "' . $input['nama'] . '" berhasil diperbarui.');
            header('Location: ' . routeUrl('user/list'));
            exit;
        }
    }
}
?>

<section class="panel">
    <div class="panel-header">
        <div>
            <h2>Ubah Pengguna #<?= $id; ?></h2>
            <p class="panel-subtitle">Kosongkan kolom kata sandi apabila tidak ingin menggantinya.</p>
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

    <form method="post" action="<?= e(routeUrl('user/edit', ['id' => $id])); ?>" class="form-layout">
        <?= csrfField(); ?>
        <input type="hidden" name="id_user" value="<?= $id; ?>">

        <div class="form-row">
            <div class="form-field">
                <label for="nama">Nama Lengkap</label>
                <input type="text" id="nama" name="nama" value="<?= e($input['nama']); ?>" required>
            </div>
            <div class="form-field">
                <label for="email">Alamat Email</label>
                <input type="email" id="email" name="email" value="<?= e($input['email']); ?>" required>
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
                <label for="password">Kata Sandi Baru (Opsional)</label>
                <input type="password" id="password" name="password" placeholder="Biarkan kosong jika tidak diganti">
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <a href="<?= e(routeUrl('user/list')); ?>" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</section>
