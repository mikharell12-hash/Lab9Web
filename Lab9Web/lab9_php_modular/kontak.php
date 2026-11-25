<?php
require_once __DIR__ . '/header.php';

$terkirim = false;
$namaPengirim = '';
$emailPengirim = '';
$isiPesan = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $namaPengirim = trim((string)($_POST['nama'] ?? ''));
    $emailPengirim = trim((string)($_POST['email'] ?? ''));
    $isiPesan = trim((string)($_POST['pesan'] ?? ''));
    if ($namaPengirim !== '' && $emailPengirim !== '' && $isiPesan !== '') {
        $terkirim = true;
    }
}
?>

<section class="content">
    <h2>Kontak Kami</h2>
    <p>Silakan kirimkan pertanyaan atau masukan terkait modul praktikum melalui formulir berikut.</p>

    <?php if ($terkirim): ?>
        <div class="notice-box">
            Terima kasih, <strong><?= e($namaPengirim); ?></strong> (<?= e($emailPengirim); ?>). Pesan Anda: <em>"<?= e($isiPesan); ?>"</em> telah diterima.
        </div>
    <?php endif; ?>

    <form method="post" action="">
        <div class="form-group">
            <label for="nama">Nama Lengkap</label>
            <input type="text" id="nama" name="nama" placeholder="Masukkan nama Anda" required>
        </div>
        <div class="form-group">
            <label for="email">Alamat Email</label>
            <input type="email" id="email" name="email" placeholder="nama@domain.com" required>
        </div>
        <div class="form-group">
            <label for="pesan">Pesan</label>
            <textarea id="pesan" name="pesan" rows="3" placeholder="Tulis pesan..." required></textarea>
        </div>
        <button type="submit" class="btn">Kirim Pesan</button>
    </form>
</section>

<?php require_once __DIR__ . '/footer.php'; ?>
