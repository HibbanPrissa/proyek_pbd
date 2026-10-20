<?php
require 'koneksi.php';

$judul = 'Data Barang';
include 'header.php';

$rows = $pdo->query("SELECT * FROM v_barang ORDER BY idbarang")->fetchAll(); 
?>

<table>
    <tr>
        <th>No</th>
        <th>Nama</th>
        <th>Jenis</th>
        <th>Satuan</th>
        <th>Harga</th>
        <th>Status</th>
    </tr>
    <?php foreach ($rows as $i => $r): ?>
    <tr>
        <td><?= $i + 1 ?></td>
        <td><?= e($r['nama']) ?></td>
        <td><?= e($r['jenis_label']) ?></td>
        <td><?= e($r['nama_satuan']) ?></td>
        <td>Rp <?= number_format($r['harga'], 0, ',', '.') ?></td>
        <td><?= e($r['status_label']) ?></td>
    </tr>
    <?php endforeach; ?>
    <?php if (!$rows): ?>
        <tr><td colspan="6" class="kosong">Belum ada data.</td></tr>
    <?php endif; ?>
</table>

<?php 
include 'footer.php'; 
?>