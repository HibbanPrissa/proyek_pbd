<?php
require 'koneksi.php';

$judul = 'Data Role';
include 'header.php';

$rows = $pdo->query("SELECT * FROM v_role ORDER BY idrole")->fetchAll(); 
?>

<table>
    <tr>
        <th>No</th>
        <th>Nama Role</th>
    </tr>
    <?php foreach ($rows as $i => $r): ?>
    <tr>
        <td><?= $i + 1 ?></td>
        <td><?= e($r['nama_role']) ?></td>
    </tr>
    <?php endforeach; ?>
    <?php if (!$rows): ?>
        <tr><td colspan="2" class="kosong">Belum ada data.</td></tr>
    <?php endif; ?>
</table>

<?php 
include 'footer.php'; 
?>