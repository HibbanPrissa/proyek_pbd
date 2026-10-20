<?php
require 'koneksi.php';
$err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $aksi = $_POST['aksi'] ?? '';
        if ($aksi === 'tambah') {
            $st = $pdo->prepare("INSERT INTO vendor (nama_vendor, badan_hukum, status) VALUES (?, ?, ?)");
            $st->execute([trim($_POST['nama_vendor']), $_POST['badan_hukum'], $_POST['status']]);
            redirect('vendor.php', 'Vendor berhasil ditambahkan.');
        } elseif ($aksi === 'ubah') {
            $st = $pdo->prepare("UPDATE vendor SET nama_vendor = ?, badan_hukum = ?, status = ? WHERE idvendor = ?");
            $st->execute([trim($_POST['nama_vendor']), $_POST['badan_hukum'], $_POST['status'], (int)$_POST['id']]);
            redirect('vendor.php', 'Vendor berhasil diubah.');
        } elseif ($aksi === 'hapus') {
            $st = $pdo->prepare("DELETE FROM vendor WHERE idvendor = ?");
            $st->execute([(int)$_POST['id']]);
            redirect('vendor.php', 'Vendor berhasil dihapus.');
        }
    } catch (PDOException $ex) {
        $err = pesanError($ex);
    }
}

$mode = $_GET['mode'] ?? 'list';
$data = null;
if ($mode === 'ubah') {
    $st = $pdo->prepare("SELECT * FROM v_vendor WHERE idvendor = ?");
    $st->execute([(int)($_GET['id'] ?? 0)]);
    $data = $st->fetch();
    if (!$data) $mode = 'list';
}

$form = ($mode === 'tambah' || $mode === 'ubah');
$judul = $form ? (($mode === 'tambah' ? 'Tambah' : 'Ubah') . ' Vendor') : 'Data Vendor';
$subjudul = $form ? '' : 'Master data pemasok barang';
$tombol = $form ? null : ['vendor.php?mode=tambah', 'Tambah Vendor'];
include 'header.php';

if ($mode === 'tambah' || $mode === 'ubah'):
    $bhNow = $_POST['badan_hukum'] ?? ($data['badan_hukum'] ?? 'P');
    $stNow = $_POST['status'] ?? ($data['status'] ?? 'A'); ?>
    <form class="isian" method="post">
        <input type="hidden" name="aksi" value="<?= $mode ?>">
        <input type="hidden" name="id" value="<?= (int)($data['idvendor'] ?? 0) ?>">

        <label>Nama Vendor</label>
        <input type="text" name="nama_vendor" maxlength="100" required value="<?= nilai('nama_vendor', $data) ?>">

        <label>Badan Hukum</label>
        <select name="badan_hukum">
            <option value="P" <?= $bhNow === 'P' ? 'selected' : '' ?>>PT</option>
            <option value="C" <?= $bhNow === 'C' ? 'selected' : '' ?>>CV</option>
            <option value="U" <?= $bhNow === 'U' ? 'selected' : '' ?>>UD</option>
        </select>

        <label>Status</label>
        <select name="status">
            <option value="A" <?= $stNow === 'A' ? 'selected' : '' ?>>Aktif</option>
            <option value="N" <?= $stNow === 'N' ? 'selected' : '' ?>>Nonaktif</option>
        </select>

        <div class="tombol">
            <button class="btn-primary" type="submit">Simpan</button>
            <a class="btn-sekunder" href="vendor.php">Batal</a>
        </div>
    </form>
<?php else:
    $rows = $pdo->query("SELECT * FROM v_vendor ORDER BY idvendor")->fetchAll(); ?>
    <div class="tabel-wrap">
    <table>
        <tr><th class="c">No</th><th>Nama Vendor</th><th>Badan Hukum</th><th class="c">Status</th><th class="kanan">Aksi</th></tr>
        <?php foreach ($rows as $i => $r): ?>
        <tr>
            <td><?= $i + 1 ?></td>
            <td><?= e($r['nama_vendor']) ?></td>
            <td><?= e($r['badan_hukum_label']) ?></td>
            <td class="c"><?= badge($r['status_label']) ?></td>
            <td class="aksi kanan">
                <a class="btn-ubah" href="vendor.php?mode=ubah&id=<?= $r['idvendor'] ?>">Ubah</a>
                <form method="post" class="form-hapus">
                    <input type="hidden" name="aksi" value="hapus">
                    <input type="hidden" name="id" value="<?= $r['idvendor'] ?>">
                    <button class="btn-hapus" type="submit">Hapus</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php if (!$rows): ?><tr><td colspan="5" class="kosong">Belum ada data.</td></tr><?php endif; ?>
    </table>
    </div>
<?php endif;
include 'footer.php';
