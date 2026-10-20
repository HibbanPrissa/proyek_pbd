<?php
require 'koneksi.php';

$daftar = [
    'Role'   => ['v_role', 'role.php'],
    'User'   => ['v_user', 'user.php'],
    'Satuan' => ['v_satuan', 'satuan.php'],
    'Vendor' => ['v_vendor', 'vendor.php'],
    'Barang' => ['v_barang', 'barang.php'],
    'Margin' => ['v_margin_penjualan', 'margin_penjualan.php'],
];

$judul = 'Dashboard';
include 'header.php';
?>
<div class="kartu-wrap">
<?php foreach ($daftar as $nama => [$view, $link]):
    $jumlah = $pdo->query("SELECT COUNT(*) FROM $view")->fetchColumn(); ?>
    <a class="kartu" href="<?= $link ?>">
        <div class="angka"><?= (int)$jumlah ?></div>
        <div><?= $nama ?></div>
    </a>
<?php endforeach; ?>
</div>
<?php include 'footer.php'; ?>
