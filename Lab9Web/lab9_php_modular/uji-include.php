<?php
/**
 * Bagian C.3 - Praktik menguji include
 * Gunakan parameter ?simulasi=hilang untuk menguji perilaku saat file tidak ditemukan.
 */
$simulasiHilang = isset($_GET['simulasi']) && $_GET['simulasi'] === 'hilang';
$targetFile = $simulasiHilang
    ? __DIR__ . '/pesan_sementara_diubah.php'
    : __DIR__ . '/pesan.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Uji Konstruksi include - Praktikum 9</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <header>
        <h1>Praktik C.3: Menguji <code>include</code></h1>
        <p>Mengamati perilaku eksekusi program ketika file tersedia vs tidak ditemukan</p>
    </header>
    <nav>
        <a href="home.php">Home</a>
        <a href="uji-include.php" class="<?= !$simulasiHilang ? 'active' : '' ?>">Include (Normal)</a>
        <a href="uji-include.php?simulasi=hilang" class="<?= $simulasiHilang ? 'active' : '' ?>">Include (File Hilang)</a>
        <a href="uji-require.php">Require (Normal)</a>
        <a href="uji-require.php?simulasi=hilang">Require (File Hilang)</a>
    </nav>
    <main>
        <section class="content">
            <h2>Hasil Eksekusi <code>include</code></h2>
            <div class="code-output">
                <?php
                echo "<p><strong>1. Sebelum pemanggilan</strong> (baris awal dieksekusi)</p>";
                include $targetFile;
                echo "<p><strong>2. Sesudah pemanggilan</strong> (baris akhir TETAP dieksekusi meskipun file tidak ditemukan pada mode include)</p>";
                ?>
            </div>
            <p class="hint">
                Catatan: Pada <code>include</code>, jika file tidak ditemukan maka PHP mengeluarkan <em>Warning</em>, namun instruksi setelah pemanggilan tetap dilanjutkan.
            </p>
        </section>
    </main>
    <footer>
        <p>&copy; <?= date('Y'); ?> - Praktikum Pemrograman Web (Alleizandro Lim - 312510395)</p>
    </footer>
</div>
</body>
</html>
