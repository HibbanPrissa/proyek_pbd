<?php
require 'koneksi.php';

$judul = 'Data Satuan';
include 'header.php';

$rows = $pdo->query("SELECT * FROM v_satuan ORDER BY idsatuan")->fetchAll(); 
?>

<table>
    <tr>
        <th>No</th>
        <th>Nama Satuan</th>
        <th>Status</th>
    </tr>
    <?php foreach ($rows as $i => $r): ?>
    <tr>
        <td><?= $i + 1 ?></td>
        <td><?= e($r['nama_satuan']) ?></td>
        <td><?= e($r['status_label']) ?></td>
    </tr>
    <?php endforeach; ?>
    <?php if (!$rows): ?>
        <tr><td colspan="3" class="kosong">Belum ada data.</td></tr>
    <?php endif; ?>
</table>

<?php 
include 'footer.php'; 
?>