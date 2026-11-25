<?php
/**
 * views/header.php
 * Komponen tampilan header dan navigasi utama bersama (Modular Layout).
 */
$userAktif = currentUser();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle ?? 'Lab9Web - PHP Modular'); ?> | Lab9Web</title>
    <link rel="stylesheet" href="<?= e(baseUrl('assets/css/style.css')); ?>">
</head>
<body>
<div class="app-shell">
    <header class="topbar">
        <div class="topbar-inner">
            <a href="<?= e(routeUrl('dashboard')); ?>" class="brand">
                <span class="brand-badge">PHP</span>
                <div class="brand-text">
                    <strong>Lab9Web Modular</strong>
                    <span>Template &bull; Function &bull; Router &bull; CRUD</span>
                </div>
            </a>

            <div class="auth-panel">
                <?php if ($userAktif !== null): ?>
                    <span class="user-pill">
                        <?= e($userAktif['nama']); ?>
                        <small>(<?= e($userAktif['role']); ?>)</small>
                    </span>
                    <form method="post" action="<?= e(routeUrl('auth/logout')); ?>" class="inline-form">
                        <?= csrfField(); ?>
                        <button type="submit" class="btn btn-sm btn-outline-light">Logout</button>
                    </form>
                <?php else: ?>
                    <span class="guest-label">Mode Tamu</span>
                    <a href="<?= e(routeUrl('auth/login')); ?>" class="btn btn-sm btn-accent">Login Admin</a>
                <?php endif; ?>
            </div>
        </div>

        <nav class="navbar" aria-label="Navigasi Utama">
            <div class="navbar-inner">
                <a href="<?= e(routeUrl('dashboard')); ?>" class="<?= navActiveClass($currentPage, 'dashboard'); ?>">Dashboard</a>
                <a href="<?= e(routeUrl('barang/list')); ?>" class="<?= navActiveClass($currentPage, 'barang/*'); ?>">Data Barang (CRUD)</a>
                <a href="<?= e(routeUrl('user/list')); ?>" class="<?= navActiveClass($currentPage, 'user/*'); ?>">Data Pengguna</a>
                <a href="<?= e(routeUrl('function/demo')); ?>" class="<?= navActiveClass($currentPage, 'function/demo'); ?>">Library Function</a>
                <a href="<?= e(routeUrl('profil')); ?>" class="<?= navActiveClass($currentPage, 'profil'); ?>">Profil</a>
                <a href="<?= e(routeUrl('kontak')); ?>" class="<?= navActiveClass($currentPage, 'kontak'); ?>">Kontak</a>
                <a href="<?= e(baseUrl('lab9_php_modular/home.php')); ?>" class="nav-secondary">Latihan Dasar &nearr;</a>
            </div>
        </nav>
    </header>

    <main class="main-container">
        <?= renderFlashMessage(); ?>
