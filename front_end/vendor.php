<?php
require 'koneksi.php';

$judul = 'Data Vendor';
include 'header.php';

$rows = $pdo->query("SELECT * FROM v_vendor ORDER BY idvendor")->fetchAll(); 
?>

<table>
    <tr>
        <th>No</th>
        <th>Nama Vendor</th>
        <th>Badan Hukum</th>
        <th>Status</th>
    </tr>
    <?php foreach ($rows as $i => $r): ?>
    <tr>
        <td><?= $i + 1 ?></td>
        <td><?= e($r['nama_vendor']) ?></td>
        <td><?= e($r['badan_hukum_label']) ?></td>
        <td><?= e($r['status_label']) ?></td>
    </tr>
    <?php endforeach; ?>
    <?php if (!$rows): ?>
        <tr><td colspan="4" class="kosong">Belum ada data.</td></tr>
    <?php endif; ?>
</table>

<?php 
include 'footer.php'; 
?>