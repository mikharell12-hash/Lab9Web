<?php
/**
 * Bagian E.5 & Latihan Function - helpers.php
 * Kumpulan fungsi pembantu untuk modul latihan dasar lab9_php_modular.
 */

function e(string $nilai): string {
    return htmlspecialchars($nilai, ENT_QUOTES, 'UTF-8');
}

function formatRupiah(float $angka): string {
    return 'Rp ' . number_format($angka, 0, ',', '.');
}

function hitungDiskon(float $harga, float $persen = 10): float {
    return $harga * ($persen / 100);
}

function hitungLuasPersegiPanjang(float $panjang, float $lebar): float {
    return $panjang * $lebar;
}

// Latihan Function 1: Konversi suhu Celcius ke Fahrenheit
function konversiCelciusKeFahrenheit(float $c): float {
    return ($c * 9 / 5) + 32;
}

// Latihan Function 2: Menentukan status/predikat nilai
function tentukanStatusNilai(float $nilai): string {
    if ($nilai >= 85 && $nilai <= 100) {
        return 'A (Sangat Baik - Lulus)';
    } elseif ($nilai >= 70 && $nilai < 85) {
        return 'B (Baik - Lulus)';
    } elseif ($nilai >= 55 && $nilai < 70) {
        return 'C (Cukup - Lulus)';
    } elseif ($nilai >= 40 && $nilai < 55) {
        return 'D (Kurang - Tidak Lulus)';
    }
    return 'E (Sangat Kurang - Tidak Lulus)';
}

// Latihan Function 3: Menghitung total belanja
function hitungTotal(float $harga, int $jumlah): float {
    return $harga * $jumlah;
}
