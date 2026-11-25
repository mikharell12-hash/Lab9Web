<?php
require_once __DIR__ . '/header.php';

$daftarBelanja = [
    ['nama' => 'SSD NVMe 1TB', 'harga' => 1150000, 'jumlah' => 2, 'diskon' => 10],
    ['nama' => 'Mechanical Keyboard', 'harga' => 450000, 'jumlah' => 1, 'diskon' => 15],
    ['nama' => 'Mouse Wireless Ergonomis', 'harga' => 225000, 'jumlah' => 3, 'diskon' => 5],
];
$grandTotal = 0;
?>

<section class="content">
    <h2>Latihan Function - Halaman 2 (Simulasi Total Belanja &amp; Diskon)</h2>
    <p>Memanggil ulang <code>hitungTotal()</code>, <code>hitungDiskon()</code>, <code>formatRupiah()</code>, dan <code>e()</code> dari file <code>helpers.php</code> pada halaman kedua.</p>

    <table class="table-clean">
        <thead>
            <tr>
                <th>Nama Barang</th>
                <th>Harga Satuan</th>
                <th>Jumlah</th>
                <th>Total Awal</th>
                <th>Diskon</th>
                <th>Total Akhir</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($daftarBelanja as $item):
                $subtotal = hitungTotal((float)$item['harga'], (int)$item['jumlah']);
                $nominalDiskon = hitungDiskon($subtotal, (float)$item['diskon']);
                $totalAkhir = $subtotal - $nominalDiskon;
                $grandTotal += $totalAkhir;
            ?>
            <tr>
                <td><?= e($item['nama']); ?></td>
                <td><?= e(formatRupiah((float)$item['harga'])); ?></td>
                <td><?= (int)$item['jumlah']; ?> unit</td>
                <td><?= e(formatRupiah($subtotal)); ?></td>
                <td><?= (int)$item['diskon']; ?>% (<?= e(formatRupiah($nominalDiskon)); ?>)</td>
                <td><strong><?= e(formatRupiah($totalAkhir)); ?></strong></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="5" style="text-align: right;">Total Pembayaran Keseluruhan</th>
                <th><?= e(formatRupiah($grandTotal)); ?></th>
            </tr>
        </tfoot>
    </table>
</section>

<?php require_once __DIR__ . '/footer.php'; ?>
