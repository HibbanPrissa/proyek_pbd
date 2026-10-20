<?php
$halaman = basename($_SERVER['SCRIPT_NAME'], '.php');

$menu = [
    [null, [
        ['index', 'Dashboard', 'dashboard', true],
    ]],
    ['Master Data', [
        ['role', 'Role', 'admin_panel_settings', true],
        ['user', 'User', 'manage_accounts', true],
        ['satuan', 'Satuan', 'straighten', true],
        ['vendor', 'Vendor', 'store', true],
        ['barang', 'Barang', 'inventory_2', true],
        ['margin_penjualan', 'Margin Penjualan', 'price_change', true],
    ]],
    ['Transaksi', [
        ['pengadaan', 'Pengadaan', 'shopping_cart', false],
        ['penerimaan', 'Penerimaan', 'move_to_inbox', false],
        ['penjualan', 'Penjualan', 'point_of_sale', false],
        ['retur', 'Retur', 'assignment_return', false],
    ]],
    ['Laporan', [
        ['kartu_stok', 'Kartu Stok', 'receipt_long', false],
    ]],
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($judul ?? 'Prissa Store') ?> - Prissa Store</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<aside class="sidebar">
    <div class="merek">Prissa Store</div>
    <nav>
        <?php foreach ($menu as [$grup, $items]): ?>
            <?php if ($grup): ?><div class="grup"><?= $grup ?></div><?php endif; ?>
            <?php foreach ($items as [$file, $label, $ikon, $ada]): ?>
                <?php if ($ada): ?>
                    <a class="nav-item <?= $halaman === $file ? 'aktif' : '' ?>" href="<?= $file ?>.php">
                        <span class="material-symbols-outlined"><?= $ikon ?></span><?= $label ?>
                    </a>
                <?php else: ?>
                    <span class="nav-item mati" title="Halaman ini belum tersedia">
                        <span class="material-symbols-outlined"><?= $ikon ?></span><?= $label ?>
                    </span>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </nav>
</aside>

<div class="konten">
    <header class="topbar">
        <span class="topbar-judul">Prissa Store Administrasi</span>
        <div class="pengguna">
            <span><?= e(PENGGUNA_NAMA) ?> - <?= e(PENGGUNA_ROLE) ?></span>
            <div class="avatar"><span class="material-symbols-outlined">person</span></div>
        </div>
    </header>

    <main>
        <div class="kepala-halaman">
            <div>
                <h1><?= e($judul ?? '') ?></h1>
                <?php if (!empty($subjudul)): ?><p class="sub"><?= e($subjudul) ?></p><?php endif; ?>
            </div>
            <?php if (!empty($tombol)): ?>
                <a class="btn-primary" href="<?= e($tombol[0]) ?>">
                    <span class="material-symbols-outlined">add</span><?= e($tombol[1]) ?>
                </a>
            <?php endif; ?>
        </div>

        <?php if (!empty($_GET['msg'])): ?>
            <div class="alert ok"><?= e($_GET['msg']) ?></div>
        <?php endif; ?>
        <?php if (!empty($err)): ?>
            <div class="alert err"><?= e($err) ?></div>
        <?php endif; ?>
