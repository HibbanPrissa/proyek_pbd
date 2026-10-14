<?php
require 'koneksi.php';
$err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $aksi = $_POST['aksi'] ?? '';
        if ($aksi === 'tambah') {
            $st = $pdo->prepare("INSERT INTO satuan (nama_satuan, status) VALUES (?, ?)");
            $st->execute([trim($_POST['nama_satuan']), (int)$_POST['status']]);
            redirect('satuan.php', 'Satuan berhasil ditambahkan.');
        } elseif ($aksi === 'ubah') {
            $st = $pdo->prepare("UPDATE satuan SET nama_satuan = ?, status = ? WHERE idsatuan = ?");
            $st->execute([trim($_POST['nama_satuan']), (int)$_POST['status'], (int)$_POST['id']]);
            redirect('satuan.php', 'Satuan berhasil diubah.');
        } elseif ($aksi === 'hapus') {
            $st = $pdo->prepare("DELETE FROM satuan WHERE idsatuan = ?");
            $st->execute([(int)$_POST['id']]);
            redirect('satuan.php', 'Satuan berhasil dihapus.');
        }
    } catch (PDOException $ex) {
        $err = pesanError($ex);
    }
}

$mode = $_GET['mode'] ?? 'list';
$data = null;
if ($mode === 'ubah') {
    $st = $pdo->prepare("SELECT * FROM v_satuan WHERE idsatuan = ?");
    $st->execute([(int)($_GET['id'] ?? 0)]);
    $data = $st->fetch();
    if (!$data) $mode = 'list';
}

$judul = 'Data Satuan';
include 'header.php';

if ($mode === 'tambah' || $mode === 'ubah'):
    $statusNow = $_POST['status'] ?? ($data['status'] ?? 1); ?>
    <form class="isian" method="post">
        <input type="hidden" name="aksi" value="<?= $mode ?>">
        <input type="hidden" name="id" value="<?= (int)($data['idsatuan'] ?? 0) ?>">

        <label>Nama Satuan</label>
        <input type="text" name="nama_satuan" maxlength="45" required value="<?= nilai('nama_satuan', $data) ?>">

        <label>Status</label>
        <select name="status">
            <option value="1" <?= $statusNow == 1 ? 'selected' : '' ?>>Aktif</option>
            <option value="0" <?= $statusNow == 0 ? 'selected' : '' ?>>Nonaktif</option>
        </select>

        <div class="tombol">
            <button class="btn" type="submit">Simpan</button>
            <a class="btn batal" href="satuan.php">Batal</a>
        </div>
    </form>
<?php else:
    $rows = $pdo->query("SELECT * FROM v_satuan ORDER BY idsatuan")->fetchAll(); ?>
    <div class="toolbar"><a class="btn" href="satuan.php?mode=tambah">+ Tambah Satuan</a></div>
    <table>
        <tr><th>No</th><th>Nama Satuan</th><th>Status</th><th>Aksi</th></tr>
        <?php foreach ($rows as $i => $r): ?>
        <tr>
            <td><?= $i + 1 ?></td>
            <td><?= e($r['nama_satuan']) ?></td>
            <td><?= e($r['status_label']) ?></td>
            <td class="aksi">
                <a class="btn edit" href="satuan.php?mode=ubah&id=<?= $r['idsatuan'] ?>">Ubah</a>
                <form method="post" onsubmit="return confirm('Hapus data ini?')">
                    <input type="hidden" name="aksi" value="hapus">
                    <input type="hidden" name="id" value="<?= $r['idsatuan'] ?>">
                    <button class="btn hapus" type="submit">Hapus</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php if (!$rows): ?><tr><td colspan="4" class="kosong">Belum ada data.</td></tr><?php endif; ?>
    </table>
<?php endif;
include 'footer.php';
