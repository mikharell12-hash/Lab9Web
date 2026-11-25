<?php require_once __DIR__ . '/header.php'; ?>

<section class="content">
    <h2>Tentang Aplikasi</h2>
    <p>Halaman ini menjelaskan tujuan modularisasi aplikasi PHP pada Praktikum 9: memisahkan tampilan bersama, fungsi pembantu, dan konten spesifik halaman agar kode mudah dipelihara dan dikembangkan dalam tim.</p>
    <ul>
        <li><strong>Template Layout:</strong> <code>header.php</code> dan <code>footer.php</code> mengelola kerangka HTML dan navigasi.</li>
        <li><strong>Library Function:</strong> <code>helpers.php</code> menyimpan fungsi yang dapat dipanggil ulang dengan parameter dan nilai return.</li>
        <li><strong>Integrasi Utama:</strong> Aplikasi penuh dengan router whitelist dan CRUD database tersedia pada direktori utama <code>Lab9Web</code>.</li>
    </ul>
</section>

<?php require_once __DIR__ . '/footer.php'; ?>
