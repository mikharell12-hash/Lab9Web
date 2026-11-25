<?php
require_once __DIR__ . '/helpers.php';
$currentScript = basename($_SERVER['PHP_SELF'] ?? 'home.php');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Aplikasi PHP Modular - Latihan Dasar</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <header>
        <h1>Aplikasi PHP Modular</h1>
        <p>Contoh pemisahan template, function helper, dan konten halaman (Latihan C, D, E, &amp; J.1)</p>
    </header>
    <nav>
        <a href="home.php" class="<?= $currentScript === 'home.php' ? 'active' : '' ?>">Home</a>
        <a href="profil.php" class="<?= $currentScript === 'profil.php' ? 'active' : '' ?>">Profil</a>
        <a href="about.php" class="<?= $currentScript === 'about.php' ? 'active' : '' ?>">Tentang</a>
        <a href="kontak.php" class="<?= $currentScript === 'kontak.php' ? 'active' : '' ?>">Kontak</a>
        <a href="latihan-function-1.php" class="<?= $currentScript === 'latihan-function-1.php' ? 'active' : '' ?>">Function 1</a>
        <a href="latihan-function-2.php" class="<?= $currentScript === 'latihan-function-2.php' ? 'active' : '' ?>">Function 2</a>
        <a href="uji-include.php">Uji Include/Require</a>
        <a href="../index.php" class="nav-cta">&rarr; Aplikasi Utama (Router + CRUD)</a>
    </nav>
    <main>
