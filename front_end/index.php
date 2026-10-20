<?php
require 'koneksi.php';

$daftar = [
    ['Role',   'v_role',             'role.php',             'admin_panel_settings'],
    ['User',   'v_user',             'user.php',             'manage_accounts'],
    ['Satuan', 'v_satuan',           'satuan.php',           'straighten'],
    ['Vendor', 'v_vendor',           'vendor.php',           'store'],
    ['Barang', 'v_barang',           'barang.php',           'inventory_2'],
    ['Margin', 'v_margin_penjualan', 'margin_penjualan.php', 'price_change'],
];

$judul = 'Dashboard';
$subjudul = 'Ringkasan jumlah data master';
include 'header.php';
?>
<div class="kartu-wrap">
<?php foreach ($daftar as [$nama, $view, $link, $ikon]):
    $jumlah = $pdo->query("SELECT COUNT(*) FROM $view")->fetchColumn(); ?>
    <a class="kartu" href="<?= $link ?>">
        <div class="kartu-atas">
            <span>Master Entitas</span>
            <span class="material-symbols-outlined"><?= $ikon ?></span>
        </div>
        <div>
            <div class="angka"><?= (int)$jumlah ?></div>
            <div class="nama"><?= $nama ?></div>
        </div>
    </a>
<?php endforeach; ?>
</div>
<?php include 'footer.php'; ?>
