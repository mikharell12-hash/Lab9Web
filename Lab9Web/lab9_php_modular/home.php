<?php require_once __DIR__ . '/header.php'; ?>

<section class="content">
    <h2>Halaman Home</h2>
    <p>Ini adalah konten khusus halaman beranda pada latihan dasar <strong>Template Multi-Halaman</strong> (Bagian D &amp; Latihan 1).</p>

    <div class="card-grid">
        <div class="card">
            <h3>1. Pemisahan Layout</h3>
            <p>Bagian <code>header.php</code> dan <code>footer.php</code> dipanggil menggunakan <code>require_once</code> sehingga tidak ada duplikasi markup HTML di setiap halaman.</p>
        </div>
        <div class="card">
            <h3>2. Reusable Helper Function</h3>
            <p>Fungsi pada <code>helpers.php</code> dipakai lintas halaman, contohnya format mata uang <?= e(formatRupiah(250000)); ?> dan konversi suhu 30&deg;C = <?= konversiCelciusKeFahrenheit(30); ?>&deg;F.</p>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/footer.php'; ?>
