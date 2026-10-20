<?php
require 'koneksi.php';

$judul = 'Data User';
include 'header.php';

$rows = $pdo->query("SELECT * FROM v_user ORDER BY iduser")->fetchAll(); 
?>

<table>
    <tr>
        <th>No</th>
        <th>Username</th>
        <th>Role</th>
    </tr>
    <?php foreach ($rows as $i => $r): ?>
    <tr>
        <td><?= $i + 1 ?></td>
        <td><?= e($r['username']) ?></td>
        <td><?= e($r['nama_role']) ?></td>
    </tr>
    <?php endforeach; ?>
    <?php if (!$rows): ?>
        <tr><td colspan="3" class="kosong">Belum ada data.</td></tr>
    <?php endif; ?>
</table>

<?php 
include 'footer.php'; 
?>