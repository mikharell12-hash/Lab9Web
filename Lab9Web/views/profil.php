<?php
/**
 * views/profil.php
 * Latihan 1: Halaman Profil menggunakan header & footer bersama serta helper function.
 */
$pageTitle = 'Profil Mahasiswa & Arsitektur Proyek';

$deskripsiArsitektur = 'Aplikasi Lab9Web dibangun dengan arsitektur modular PHP yang memisahkan titik masuk router (index.php), konfigurasi database PDO (config/database.php), fungsi pembantu umum (helpers/functions.php), komponen antarmuka (views/), dan modul domain fitur (modules/barang, modules/user, serta modules/auth).';
?>

<section class="panel">
    <div class="panel-header">
        <div>
            <h2>Profil Mahasiswa &amp; Identitas Praktikum</h2>
            <p class="panel-subtitle">Halaman ini dimuat melalui rute <code>index.php?page=profil</code> menggunakan template bersama.</p>
        </div>
    </div>

    <div class="two-col">
        <div class="subcard">
            <h3>Data Mahasiswa</h3>
            <table class="kv-table">
                <tr>
                    <th>Nama</th>
                    <td>Alleizandro Lim</td>
                </tr>
                <tr>
                    <th>NIM</th>
                    <td>312510395</td>
                </tr>
                <tr>
                    <th>Kelas</th>
                    <td>I252A</td>
                </tr>
                <tr>
                    <th>Program Studi</th>
                    <td>Teknik Informatika</td>
                </tr>
                <tr>
                    <th>Mata Kuliah</th>
                    <td>Pemrograman Web</td>
                </tr>
                <tr>
                    <th>Topik Praktikum</th>
                    <td>Praktikum 9 - PHP Modular</td>
                </tr>
            </table>
        </div>

        <div class="subcard">
            <h3>Ringkasan Arsitektur Modular</h3>
            <p><strong>Teks Penuh:</strong> <?= e($deskripsiArsitektur); ?></p>
            <p><strong>Hasil <code>ringkasTeks(110)</code>:</strong> <em><?= e(ringkasTeks($deskripsiArsitektur, 110)); ?></em></p>
            <hr class="divider">
            <p><strong>Contoh Pemanggilan Helper Lainnya:</strong></p>
            <ul class="compact-list">
                <li>Validasi email akademik <code>alleizandro@kampus.ac.id</code>:
                    <strong><?= validasiEmail('alleizandro@kampus.ac.id') ? 'Valid' : 'Tidak Valid'; ?></strong>
                </li>
                <li>Predikat nilai praktikum (skor 90):
                    <strong><?= e(tentukanStatusNilai(90)); ?></strong>
                </li>
            </ul>
        </div>
    </div>
</section>
