<?php
require 'koneksi.php';

$judul = 'Data Margin Penjualan';
include 'header.php';

$rows = $pdo->query("SELECT * FROM v_margin_penjualan ORDER BY idmargin_penjualan")->fetchAll(); 
?>

<table>
    <tr>
        <th>No</th>
        <th>Persen</th>
        <th>Status</th>
        <th>Dibuat Oleh</th>
        <th>Diperbarui</th>
    </tr>
    <?php foreach ($rows as $i => $r): ?>
    <tr>
        <td><?= $i + 1 ?></td>
        <td><?= e(rtrim(rtrim(number_format($r['persen'], 2, ',', '.'), '0'), ',')) ?>%</td>
        <td><?= e($r['status_label']) ?></td>
        <td><?= e($r['dibuat_oleh']) ?></td>
        <td><?= e($r['updated_at']) ?></td>
    </tr>
    <?php endforeach; ?>
    <?php if (!$rows): ?>
        <tr><td colspan="5" class="kosong">Belum ada data.</td></tr>
    <?php endif; ?>
</table>

<?php 
include 'footer.php'; 
?>