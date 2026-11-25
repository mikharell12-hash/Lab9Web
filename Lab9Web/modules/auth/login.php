<?php
/**
 * modules/auth/login.php
 * Modul autentikasi pengguna (Tantangan Mandiri: pembatasan akses halaman modul).
 */
if (isLoggedIn()) {
    header('Location: ' . routeUrl('dashboard'));
    exit;
}

$pageTitle = 'Login Aplikasi';
$errors = [];
$emailInput = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? null)) {
        $errors[] = 'Token keamanan CSRF tidak valid.';
    } else {
        $emailInput = strtolower(trim((string)($_POST['email'] ?? '')));
        $passwordInput = (string)($_POST['password'] ?? '');

        if (!validasiEmail($emailInput)) {
            $errors[] = 'Masukkan alamat email yang valid.';
        }
        if ($passwordInput === '') {
            $errors[] = 'Kata sandi wajib diisi.';
        }

        if (empty($errors)) {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
            $stmt->execute([':email' => $emailInput]);
            $akun = $stmt->fetch();

            if (!$akun || !password_verify($passwordInput, (string)$akun['password'])) {
                $errors[] = 'Kombinasi email dan kata sandi tidak cocok.';
            } else {
                session_regenerate_id(true);
                $_SESSION['auth_user'] = [
                    'id_user' => (int)$akun['id_user'],
                    'nama'    => (string)$akun['nama'],
                    'email'   => (string)$akun['email'],
                    'role'    => (string)$akun['role'],
                ];

                $tujuan = $_SESSION['intended_page'] ?? 'dashboard';
                unset($_SESSION['intended_page']);

                setFlashMessage('success', 'Selamat datang kembali, ' . $akun['nama'] . '!');
                header('Location: ' . routeUrl($tujuan));
                exit;
            }
        }
    }
}
?>

<section class="panel login-card">
    <div class="panel-header">
        <div>
            <h2>Login Pengelola</h2>
            <p class="panel-subtitle">Masuk untuk menambah/mengubah data inventaris dan mengelola modul pengguna.</p>
        </div>
    </div>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-error">
            <ul class="error-list">
                <?php foreach ($errors as $err): ?>
                    <li><?= e($err); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="info-strip" style="margin-bottom: 16px;">
        <strong>Akun Demo Praktikum:</strong><br>
        Email: <code>admin@kampus.ac.id</code> &bull; Password: <code>admin123</code>
    </div>

    <form method="post" action="<?= e(routeUrl('auth/login')); ?>" class="form-layout">
        <?= csrfField(); ?>
        <div class="form-field">
            <label for="email">Alamat Email</label>
            <input type="email" id="email" name="email" value="<?= e($emailInput !== '' ? $emailInput : 'admin@kampus.ac.id'); ?>" required>
        </div>
        <div class="form-field">
            <label for="password">Kata Sandi</label>
            <input type="password" id="password" name="password" placeholder="Masukkan kata sandi" required>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Masuk</button>
            <a href="<?= e(routeUrl('dashboard')); ?>" class="btn btn-secondary">Kembali</a>
        </div>
    </form>
</section>
