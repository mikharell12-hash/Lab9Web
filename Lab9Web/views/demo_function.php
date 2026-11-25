<?php
/**
 * views/demo_function.php
 * Halaman demonstrasi & pengujian interaktif seluruh function pada helpers/functions.php
 * (Latihan 2 & Bagian E).
 */
$pageTitle = 'Pengujian Library Function';

$hargaInput   = isset($_GET['harga']) && is_numeric($_GET['harga']) ? (float)$_GET['harga'] : 350000;
$jumlahInput  = isset($_GET['jumlah']) && is_numeric($_GET['jumlah']) ? max(1, (int)$_GET['jumlah']) : 2;
$persenInput  = isset($_GET['diskon']) && is_numeric($_GET['diskon']) ? (float)$_GET['diskon'] : 15;
$emailInput   = isset($_GET['email']) ? trim((string)$_GET['email']) : 'alleizandro.lim@kampus.ac.id';
$suhuInput    = isset($_GET['suhu']) && is_numeric($_GET['suhu']) ? (float)$_GET['suhu'] : 28;
$nilaiInput   = isset($_GET['nilai']) && is_numeric($_GET['nilai']) ? (float)$_GET['nilai'] : 88;
$teksInput    = isset($_GET['teks']) && trim((string)$_GET['teks']) !== ''
    ? (string)$_GET['teks']
    : 'Modularisasi program PHP memisahkan template tampilan, konfigurasi koneksi database, pustaka fungsi, serta modul CRUD agar aplikasi lebih aman dan mudah dirawat.';

$totalBelanja  = hitungTotal($hargaInput, $jumlahInput);
$nominalDiskon = hitungDiskon($totalBelanja, $persenInput);
$totalBayar    = $totalBelanja - $nominalDiskon;
$statusEmail   = validasiEmail($emailInput);
$hasilRingkas  = ringkasTeks($teksInput, 70);
$hasilFahren   = konversiCelciusKeFahrenheit($suhuInput);
$predikatNilai = tentukanStatusNilai($nilaiInput);
?>

<section class="panel">
    <div class="panel-header">
        <div>
            <h2>Library Function (<code>helpers/functions.php</code>)</h2>
            <p class="panel-subtitle">Menguji parameter dan nilai kembalian (<code>return</code>) dari fungsi-fungsi modular pada Latihan 2 dan Bagian E.</p>
        </div>
    </div>

    <form method="get" action="<?= e(baseUrl('index.php')); ?>" class="form-layout subcard" style="margin-bottom: 20px;">
        <input type="hidden" name="page" value="function/demo">
        <div class="form-row">
            <div class="form-field">
                <label for="harga">Harga Satuan (Rp)</label>
                <input type="number" id="harga" name="harga" value="<?= e((string)$hargaInput); ?>" min="0">
            </div>
            <div class="form-field">
                <label for="jumlah">Jumlah Unit</label>
                <input type="number" id="jumlah" name="jumlah" value="<?= e((string)$jumlahInput); ?>" min="1">
            </div>
            <div class="form-field">
                <label for="diskon">Diskon (%)</label>
                <input type="number" id="diskon" name="diskon" value="<?= e((string)$persenInput); ?>" min="0" max="100" step="0.5">
            </div>
        </div>
        <div class="form-row">
            <div class="form-field">
                <label for="email">Uji Email</label>
                <input type="text" id="email" name="email" value="<?= e($emailInput); ?>">
            </div>
            <div class="form-field">
                <label for="suhu">Suhu (&deg;C)</label>
                <input type="number" id="suhu" name="suhu" value="<?= e((string)$suhuInput); ?>" step="0.1">
            </div>
            <div class="form-field">
                <label for="nilai">Nilai Akademik (0-100)</label>
                <input type="number" id="nilai" name="nilai" value="<?= e((string)$nilaiInput); ?>" min="0" max="100">
            </div>
        </div>
        <div class="form-field">
            <label for="teks">Teks Panjang untuk <code>ringkasTeks()</code></label>
            <input type="text" id="teks" name="teks" value="<?= e($teksInput); ?>">
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Jalankan Semua Function</button>
            <a href="<?= e(routeUrl('function/demo')); ?>" class="btn btn-secondary">Reset Nilai Contoh</a>
        </div>
    </form>

    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Nama Function</th>
                    <th>Argumen Uji</th>
                    <th>Nilai Kembalian (Return)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><code>hitungTotal($harga, $jumlah)</code></td>
                    <td><code>(<?= $hargaInput; ?>, <?= $jumlahInput; ?>)</code></td>
                    <td><strong><?= e(formatRupiah($totalBelanja)); ?></strong></td>
                </tr>
                <tr>
                    <td><code>hitungDiskon($harga, $persen)</code></td>
                    <td><code>(<?= $totalBelanja; ?>, <?= $persenInput; ?>)</code></td>
                    <td>Potongan <strong><?= e(formatRupiah($nominalDiskon)); ?></strong> &rarr; Bayar: <strong><?= e(formatRupiah($totalBayar)); ?></strong></td>
                </tr>
                <tr>
                    <td><code>formatRupiah($angka)</code></td>
                    <td><code>(<?= $totalBayar; ?>)</code></td>
                    <td><code><?= e(formatRupiah($totalBayar)); ?></code></td>
                </tr>
                <tr>
                    <td><code>validasiEmail($email)</code></td>
                    <td><code>("<?= e($emailInput); ?>")</code></td>
                    <td>
                        <?php if ($statusEmail): ?>
                            <span class="badge badge-green">true (Format Email Valid)</span>
                        <?php else: ?>
                            <span class="badge badge-red">false (Format Email Tidak Valid)</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <td><code>ringkasTeks($teks, 70)</code></td>
                    <td><?= mb_strlen($teksInput, 'UTF-8'); ?> karakter</td>
                    <td><em>"<?= e($hasilRingkas); ?>"</em></td>
                </tr>
                <tr>
                    <td><code>konversiCelciusKeFahrenheit($c)</code></td>
                    <td><code>(<?= $suhuInput; ?>)</code></td>
                    <td><strong><?= number_format($hasilFahren, 2, ',', '.'); ?> &deg;F</strong></td>
                </tr>
                <tr>
                    <td><code>tentukanStatusNilai($nilai)</code></td>
                    <td><code>(<?= $nilaiInput; ?>)</code></td>
                    <td><span class="badge badge-blue"><?= e($predikatNilai); ?></span></td>
                </tr>
            </tbody>
        </table>
    </div>
</section>
