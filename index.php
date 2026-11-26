<?php
require __DIR__ . '/config.php';
$jumlahPelanggan = (int)$pdo->query('SELECT COUNT(*) FROM pelanggan')->fetchColumn();
$jumlahJenis = (int)$pdo->query('SELECT COUNT(*) FROM jenis_pelanggan')->fetchColumn();
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Data Pelanggan - Web1</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header class="topbar">
    <div class="container nav">
        <a class="brand" href="index.php">Web1 <span>Data Pelanggan</span></a>
        <nav>
            <a href="pelanggan.php">Pelanggan</a>
            <a href="jenis_pelanggan.php">Jenis Pelanggan</a>
        </nav>
    </div>
</header>
<main class="container">
    <section class="hero">
        <p class="eyebrow">PENGELOLAAN DATA</p>
        <h1>Data Pelanggan</h1>
        <p>Kelola data pelanggan dan jenis pelanggan pada database <code>web1_pertemuan09</code>.</p>
    </section>
    <section class="stats">
        <article class="stat"><span>Total pelanggan</span><strong><?= $jumlahPelanggan ?></strong><a href="pelanggan.php">Kelola pelanggan →</a></article>
        <article class="stat"><span>Jenis pelanggan</span><strong><?= $jumlahJenis ?></strong><a href="jenis_pelanggan.php">Kelola jenis →</a></article>
    </section>
    <p class="notice">Pastikan tabel tambahan sudah dibuat dengan menjalankan <code>database_tambahan.sql</code> di phpMyAdmin.</p>
</main>
</body>
</html>
