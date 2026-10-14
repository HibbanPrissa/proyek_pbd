<?php /* header.php - kerangka atas halaman */ ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($judul ?? 'Aplikasi Inventori') ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<nav>
    <a class="brand" href="index.php">Inventori</a>
    <a href="role.php">Role</a>
    <a href="user.php">User</a>
    <a href="satuan.php">Satuan</a>
    <a href="vendor.php">Vendor</a>
    <a href="barang.php">Barang</a>
    <a href="margin_penjualan.php">Margin</a>
</nav>
<main>
    <h1><?= e($judul ?? '') ?></h1>
    <?php if (!empty($_GET['msg'])): ?>
        <div class="alert ok"><?= e($_GET['msg']) ?></div>
    <?php endif; ?>
    <?php if (!empty($err)): ?>
        <div class="alert err"><?= e($err) ?></div>
    <?php endif; ?>
