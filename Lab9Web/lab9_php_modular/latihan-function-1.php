<?php
require_once __DIR__ . '/header.php';

$suhuC = isset($_GET['c']) && is_numeric($_GET['c']) ? (float)$_GET['c'] : 36.5;
$nilaiUji = isset($_GET['nilai']) && is_numeric($_GET['nilai']) ? (float)$_GET['nilai'] : 88;
?>

<section class="content">
    <h2>Latihan Function - Halaman 1 (Konversi Suhu &amp; Predikat Nilai)</h2>
    <p>Memanggil function <code>konversiCelciusKeFahrenheit()</code> dan <code>tentukanStatusNilai()</code> dari file bersama <code>helpers.php</code>.</p>

    <div class="card-grid">
        <div class="card">
            <h3>Konversi Celcius ke Fahrenheit</h3>
            <p>Input suhu: <strong><?= $suhuC; ?> &deg;C</strong></p>
            <p>Hasil konversi: <strong><?= number_format(konversiCelciusKeFahrenheit($suhuC), 2, ',', '.'); ?> &deg;F</strong></p>
        </div>
        <div class="card">
            <h3>Status / Predikat Nilai</h3>
            <p>Nilai mahasiswa: <strong><?= $nilaiUji; ?></strong></p>
            <p>Predikat: <strong><?= e(tentukanStatusNilai($nilaiUji)); ?></strong></p>
        </div>
    </div>

    <h3 style="margin-top: 24px;">Tabel Pengujian Rentang Nilai &amp; Suhu</h3>
    <table class="table-clean">
        <thead>
            <tr>
                <th>Sampel Suhu (&deg;C)</th>
                <th>Hasil (&deg;F)</th>
                <th>Sampel Nilai</th>
                <th>Predikat Nilai</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sampel = [
                ['c' => 0, 'n' => 92],
                ['c' => 25, 'n' => 78],
                ['c' => 37, 'n' => 64],
                ['c' => 100, 'n' => 48],
            ];
            foreach ($sampel as $baris):
            ?>
            <tr>
                <td><?= $baris['c']; ?> &deg;C</td>
                <td><?= konversiCelciusKeFahrenheit($baris['c']); ?> &deg;F</td>
                <td><?= $baris['n']; ?></td>
                <td><?= e(tentukanStatusNilai($baris['n'])); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>

<?php require_once __DIR__ . '/footer.php'; ?>
