<?php
/**
 * modules/user/delete.php
 * Modul hapus pengguna melalui metode POST dan proteksi CSRF.
 */
requireLogin('user/list');

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verifyCsrfToken($_POST['csrf_token'] ?? null)) {
    setFlashMessage('error', 'Permintaan penghapusan pengguna tidak sah.');
    header('Location: ' . routeUrl('user/list'));
    exit;
}

$id = isset($_POST['id_user']) && filter_var($_POST['id_user'], FILTER_VALIDATE_INT) !== false
    ? (int)$_POST['id_user']
    : 0;

if ($id === (int)(currentUser()['id_user'] ?? 0)) {
    setFlashMessage('warning', 'Anda tidak dapat menghapus akun yang sedang digunakan login.');
    header('Location: ' . routeUrl('user/list'));
    exit;
}

$stmtCek = $pdo->prepare("SELECT nama FROM users WHERE id_user = :id");
$stmtCek->execute([':id' => $id]);
$target = $stmtCek->fetch();

if (!$target) {
    setFlashMessage('error', 'Data pengguna tidak ditemukan.');
    header('Location: ' . routeUrl('user/list'));
    exit;
}

$stmtDel = $pdo->prepare("DELETE FROM users WHERE id_user = :id");
$stmtDel->execute([':id' => $id]);

setFlashMessage('success', 'Akun pengguna "' . $target['nama'] . '" berhasil dihapus.');
header('Location: ' . routeUrl('user/list'));
exit;
