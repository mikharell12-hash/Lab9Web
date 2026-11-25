<?php
/**
 * views/kontak.php
 * Latihan 1 & Latihan 2: Halaman Kontak dengan validasiEmail() dan ringkasTeks().
 */
$pageTitle = 'Kontak & Umpan Balik';

$errors = [];
$hasilKontak = null;
$form = [
    'nama'   => '',
    'email'  => '',
    'subjek' => '',
    'pesan'  => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? null)) {
        $errors[] = 'Sesi formulir tidak valid. Silakan muat ulang halaman.';
    } else {
        $form['nama']   = trim((string)($_POST['nama'] ?? ''));
        $form['email']  = trim((string)($_POST['email'] ?? ''));
        $form['subjek'] = trim((string)($_POST['subjek'] ?? ''));
        $form['pesan']  = trim((string)($_POST['pesan'] ?? ''));

        if ($form['nama'] === '') {
            $errors[] = 'Nama pengirim wajib diisi.';
        }
        if (!validasiEmail($form['email'])) {
            $errors[] = 'Alamat email tidak valid (diperiksa menggunakan validasiEmail()).';
        }
        if ($form['subjek'] === '') {
            $errors[] = 'Subjek pesan wajib diisi.';
        }
        if (mb_strlen($form['pesan'], 'UTF-8') < 10) {
            $errors[] = 'Isi pesan minimal 10 karakter.';
        }

        if (empty($errors)) {
            $hasilKontak = [
                'nama'      => $form['nama'],
                'email'     => $form['email'],
                'subjek'    => $form['subjek'],
                'ringkasan' => ringkasTeks($form['pesan'], 80),
            ];
            $form = ['nama' => '', 'email' => '', 'subjek' => '', 'pesan' => ''];
        }
    }
}
?>

<section class="panel">
    <div class="panel-header">
        <div>
            <h2>Hubungi Pengelola Aplikasi</h2>
            <p class="panel-subtitle">Menggunakan template bersama serta fungsi <code>validasiEmail()</code> dan <code>ringkasTeks()</code> dari <code>helpers/functions.php</code>.</p>
        </div>
    </div>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-error">
            <strong>Periksa kembali input Anda:</strong>
            <ul class="error-list">
                <?php foreach ($errors as $err): ?>
                    <li><?= e($err); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if ($hasilKontak !== null): ?>
        <div class="alert alert-success">
            <strong>Pesan berhasil divalidasi!</strong><br>
            Pengirim: <?= e($hasilKontak['nama']); ?> (<?= e($hasilKontak['email']); ?>) &bull;
            Subjek: <?= e($hasilKontak['subjek']); ?><br>
            Pratinjau ringkas: <em>"<?= e($hasilKontak['ringkasan']); ?>"</em>
        </div>
    <?php endif; ?>

    <form method="post" action="<?= e(routeUrl('kontak')); ?>" class="form-layout">
        <?= csrfField(); ?>
        <div class="form-row">
            <div class="form-field">
                <label for="nama">Nama Lengkap</label>
                <input type="text" id="nama" name="nama" value="<?= e($form['nama']); ?>" placeholder="Contoh: Alleizandro Lim" required>
            </div>
            <div class="form-field">
                <label for="email">Alamat Email</label>
                <input type="text" id="email" name="email" value="<?= e($form['email']); ?>" placeholder="Contoh: mahasiswa@kampus.ac.id" required>
            </div>
        </div>
        <div class="form-field">
            <label for="subjek">Subjek</label>
            <input type="text" id="subjek" name="subjek" value="<?= e($form['subjek']); ?>" placeholder="Pertanyaan seputar modul atau inventaris" required>
        </div>
        <div class="form-field">
            <label for="pesan">Isi Pesan</label>
            <textarea id="pesan" name="pesan" rows="4" placeholder="Tuliskan pesan minimal 10 karakter..." required><?= e($form['pesan']); ?></textarea>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Kirim Pesan</button>
            <button type="reset" class="btn btn-secondary">Reset</button>
        </div>
    </form>
</section>
