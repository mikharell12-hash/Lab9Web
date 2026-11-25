<?php
/**
 * helpers/functions.php
 * Pustaka fungsi pembantu aplikasi (Latihan 2, Bagian E, Keamanan, & Tantangan Mandiri).
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Menghasilkan base URL aplikasi secara konsisten baik diakses lewat index.php?page=...
 * maupun lewat URL rewrite .htaccess (misalnya /Lab9Web/barang/list).
 */
function baseUrl(string $path = ''): string {
    $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/index.php'));
    $base = rtrim($scriptDir, '/');
    $cleanPath = ltrim($path, '/');

    if ($cleanPath === '') {
        return ($base === '' ? '' : $base) . '/';
    }
    return ($base === '' ? '' : $base) . '/' . $cleanPath;
}

/**
 * Menghasilkan URL rute dengan pola standar index.php?page=...
 */
function routeUrl(string $page = 'dashboard', array $params = []): string {
    $query = array_merge(['page' => $page], $params);
    return baseUrl('index.php?' . http_build_query($query));
}

/**
 * Melakukan escaping karakter khusus HTML untuk mencegah XSS.
 */
function e(?string $nilai): string {
    return htmlspecialchars((string)$nilai, ENT_QUOTES, 'UTF-8');
}

// ============================================================================
// LATIHAN 2 - EMPAT FUNCTION WAJIB (formatRupiah, hitungDiskon, validasiEmail, ringkasTeks)
// ============================================================================

/**
 * 1. Memformat angka menjadi representasi mata uang Rupiah.
 */
function formatRupiah(float $angka): string {
    return 'Rp ' . number_format($angka, 0, ',', '.');
}

/**
 * 2. Menghitung nominal potongan diskon berdasarkan harga dan persentase.
 */
function hitungDiskon(float $harga, float $persen = 10): float {
    if ($harga < 0 || $persen < 0) {
        return 0.0;
    }
    return $harga * ($persen / 100);
}

/**
 * 3. Memeriksa apakah alamat email memiliki format yang valid.
 */
function validasiEmail(string $email): bool {
    $bersih = trim($email);
    return $bersih !== '' && filter_var($bersih, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * 4. Meringkas teks panjang hingga batas karakter tertentu tanpa memotong kata secara kasar.
 */
function ringkasTeks(string $teks, int $batas = 65, string $akhiran = '...'): string {
    $teksBersih = trim(preg_replace('/\s+/', ' ', $teks) ?? $teks);
    if (mb_strlen($teksBersih, 'UTF-8') <= $batas) {
        return $teksBersih;
    }
    $potongan = mb_substr($teksBersih, 0, $batas, 'UTF-8');
    $spasiTerakhir = mb_strrpos($potongan, ' ', 0, 'UTF-8');
    if ($spasiTerakhir !== false && $spasiTerakhir > (int)($batas * 0.5)) {
        $potongan = mb_substr($potongan, 0, $spasiTerakhir, 'UTF-8');
    }
    return rtrim($potongan, " ,.;:-") . $akhiran;
}

// ============================================================================
// BAGIAN E - FUNCTION LATIHAN TAMBAHAN
// ============================================================================

/**
 * Mengonversi suhu dari derajat Celcius ke derajat Fahrenheit.
 */
function konversiCelciusKeFahrenheit(float $c): float {
    return ($c * 9 / 5) + 32;
}

/**
 * Menentukan predikat nilai akademik berdasarkan rentang skor 0-100.
 */
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

/**
 * Menghitung total harga belanja berdasarkan harga satuan dan kuantitas.
 */
function hitungTotal(float $harga, int $jumlah): float {
    return $harga * $jumlah;
}

// ============================================================================
// TANTANGAN MANDIRI - MENU AKTIF, FLASH MESSAGE, CSRF, & PEMBATASAN AKSES LOGIN
// ============================================================================

/**
 * Mengembalikan class 'active' apabila rute saat ini cocok dengan daftar rute atau awalan modul.
 */
function navActiveClass(string $currentPage, string|array $target): string {
    $targets = is_array($target) ? $target : [$target];
    foreach ($targets as $item) {
        if (str_ends_with($item, '/*')) {
            $prefix = substr($item, 0, -1); // contoh: 'barang/'
            if (str_starts_with($currentPage, $prefix)) {
                return 'active';
            }
        } elseif ($currentPage === $item) {
            return 'active';
        }
    }
    return '';
}

/**
 * Menyimpan pesan notifikasi satu kali tampil (flash message) setelah operasi CRUD/autentikasi.
 */
function setFlashMessage(string $tipe, string $pesan): void {
    $_SESSION['flash_message'] = [
        'tipe'  => $tipe, // 'success', 'error', 'warning', 'info'
        'pesan' => $pesan,
    ];
}

/**
 * Mengambil dan menghapus flash message dari session, lalu mengembalikan markup HTML-nya.
 */
function renderFlashMessage(): string {
    if (empty($_SESSION['flash_message']) || !is_array($_SESSION['flash_message'])) {
        return '';
    }
    $flash = $_SESSION['flash_message'];
    unset($_SESSION['flash_message']);

    $tipeValid = in_array($flash['tipe'], ['success', 'error', 'warning', 'info'], true)
        ? $flash['tipe']
        : 'info';

    $label = match ($tipeValid) {
        'success' => 'Berhasil',
        'error'   => 'Gagal',
        'warning' => 'Perhatian',
        default   => 'Informasi',
    };

    return sprintf(
        '<div class="alert alert-%s" role="alert"><strong>%s:</strong> %s</div>',
        e($tipeValid),
        e($label),
        e((string)$flash['pesan'])
    );
}

/**
 * Menghasilkan token CSRF unik per sesi untuk mengamankan pengiriman form POST.
 */
function csrfToken(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Menghasilkan elemen input hidden berisi token CSRF.
 */
function csrfField(): string {
    return '<input type="hidden" name="csrf_token" value="' . e(csrfToken()) . '">';
}

/**
 * Memverifikasi kecocokan token CSRF dari permintaan POST.
 */
function verifyCsrfToken(?string $token): bool {
    if (!is_string($token) || $token === '' || empty($_SESSION['csrf_token'])) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Memeriksa apakah pengguna sudah login di session.
 */
function isLoggedIn(): bool {
    return !empty($_SESSION['auth_user']) && is_array($_SESSION['auth_user']);
}

/**
 * Mengambil informasi pengguna yang sedang login.
 */
function currentUser(): ?array {
    return isLoggedIn() ? $_SESSION['auth_user'] : null;
}

/**
 * Membatasi akses halaman modul tertentu agar hanya dapat dibuka setelah login.
 */
function requireLogin(string $redirectAfterLogin = 'dashboard'): void {
    if (!isLoggedIn()) {
        $_SESSION['intended_page'] = $redirectAfterLogin;
        setFlashMessage('warning', 'Halaman tersebut memerlukan autentikasi. Silakan login terlebih dahulu.');
        header('Location: ' . routeUrl('auth/login'));
        exit;
    }
}
