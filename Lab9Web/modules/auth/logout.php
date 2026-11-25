<?php
/**
 * modules/auth/logout.php
 * Mengakhiri sesi login pengguna secara aman melalui metode POST.
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && verifyCsrfToken($_POST['csrf_token'] ?? null)) {
    unset($_SESSION['auth_user'], $_SESSION['intended_page']);
    session_regenerate_id(true);
    setFlashMessage('info', 'Anda telah keluar dari sesi aplikasi.');
}

header('Location: ' . routeUrl('dashboard'));
exit;
