<?php
/**
 * modules/barang/delete.php
 * Modul Delete Data Barang menggunakan metode POST dan validasi CSRF (Praktik Keamanan Halaman 9).
 */
requireLogin('barang/list');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    setFlashMessage('warning', 'Penghapusan data tidak diizinkan melalui permintaan GET.');
    header('Location: ' . routeUrl('barang/list'));
    exit;
}

if (!verifyCsrfToken($_POST['csrf_token'] ?? null)) {
    setFlashMessage('error', 'Token CSRF tidak valid. Operasi penghapusan dibatalkan.');
    header('Location: ' . routeUrl('barang/list'));
    exit;
}

$id = isset($_POST['id_barang']) && filter_var($_POST['id_barang'], FILTER_VALIDATE_INT) !== false
    ? (int)$_POST['id_barang']
    : 0;

$stmtCek = $pdo->prepare("SELECT nama FROM data_barang WHERE id_barang = :id");
$stmtCek->execute([':id' => $id]);
$barang = $stmtCek->fetch();

if (!$barang) {
    setFlashMessage('error', 'Data barang yang ingin dihapus tidak ditemukan.');
    header('Location: ' . routeUrl('barang/list'));
    exit;
}

$stmtHapus = $pdo->prepare("DELETE FROM data_barang WHERE id_barang = :id");
$stmtHapus->execute([':id' => $id]);

setFlashMessage('success', 'Data barang "' . $barang['nama'] . '" telah dihapus dari database.');
header('Location: ' . routeUrl('barang/list'));
exit;
