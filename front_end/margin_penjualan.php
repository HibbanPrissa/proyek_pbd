<?php
require 'koneksi.php';
$err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $aksi = $_POST['aksi'] ?? '';
        if ($aksi === 'tambah') {
            $st = $pdo->prepare("INSERT INTO margin_penjualan (persen, status, iduser) VALUES (?, ?, ?)");
            $st->execute([(float)$_POST['persen'], (int)$_POST['status'], (int)$_POST['iduser']]);
            redirect('margin_penjualan.php', 'Margin berhasil ditambahkan.');
        } elseif ($aksi === 'ubah') {
            $st = $pdo->prepare("UPDATE margin_penjualan SET persen = ?, status = ?, iduser = ? WHERE idmargin_penjualan = ?");
            $st->execute([(float)$_POST['persen'], (int)$_POST['status'], (int)$_POST['iduser'], (int)$_POST['id']]);
            redirect('margin_penjualan.php', 'Margin berhasil diubah.');
        } elseif ($aksi === 'hapus') {
            $st = $pdo->prepare("DELETE FROM margin_penjualan WHERE idmargin_penjualan = ?");
            $st->execute([(int)$_POST['id']]);
            redirect('margin_penjualan.php', 'Margin berhasil dihapus.');
        }
    } catch (PDOException $ex) {
        $err = pesanError($ex);
    }
}

$mode = $_GET['mode'] ?? 'list';
$data = null;
if ($mode === 'ubah') {
    $st = $pdo->prepare("SELECT * FROM v_margin_penjualan WHERE idmargin_penjualan = ?");
    $st->execute([(int)($_GET['id'] ?? 0)]);
    $data = $st->fetch();
    if (!$data) $mode = 'list';
}

$judul = 'Data Margin Penjualan';
include 'header.php';

if ($mode === 'tambah' || $mode === 'ubah'):
    $userNow   = $_POST['iduser'] ?? ($data['iduser'] ?? '');
    $statusNow = $_POST['status'] ?? ($data['status'] ?? 1);
    $users = $pdo->query("SELECT iduser, username FROM v_user ORDER BY username")->fetchAll(); ?>
    <form class="isian" method="post">
        <input type="hidden" name="aksi" value="<?= $mode ?>">
        <input type="hidden" name="id" value="<?= (int)($data['idmargin_penjualan'] ?? 0) ?>">

        <label>Persen Margin (%)</label>
        <input type="number" name="persen" min="0" step="0.01" required value="<?= nilai('persen', $data) ?>">

        <label>Status</label>
        <select name="status">
            <option value="1" <?= $statusNow == 1 ? 'selected' : '' ?>>Aktif</option>
            <option value="0" <?= $statusNow == 0 ? 'selected' : '' ?>>Nonaktif</option>
        </select>

        <label>Dibuat Oleh</label>
        <select name="iduser" required>
            <option value="">-- pilih user --</option>
            <?php foreach ($users as $u): ?>
                <option value="<?= $u['iduser'] ?>" <?= $userNow == $u['iduser'] ? 'selected' : '' ?>>
                    <?= e($u['username']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <div class="tombol">
            <button class="btn" type="submit">Simpan</button>
            <a class="btn batal" href="margin_penjualan.php">Batal</a>
        </div>
    </form>
<?php else:
    $rows = $pdo->query("SELECT * FROM v_margin_penjualan ORDER BY idmargin_penjualan")->fetchAll(); ?>
    <div class="toolbar"><a class="btn" href="margin_penjualan.php?mode=tambah">+ Tambah Margin</a></div>
    <table>
        <tr><th>No</th><th>Persen</th><th>Status</th><th>Dibuat Oleh</th><th>Diperbarui</th><th>Aksi</th></tr>
        <?php foreach ($rows as $i => $r): ?>
        <tr>
            <td><?= $i + 1 ?></td>
            <td><?= e(rtrim(rtrim(number_format($r['persen'], 2, ',', '.'), '0'), ',')) ?>%</td>
            <td><?= e($r['status_label']) ?></td>
            <td><?= e($r['dibuat_oleh']) ?></td>
            <td><?= e($r['updated_at']) ?></td>
            <td class="aksi">
                <a class="btn edit" href="margin_penjualan.php?mode=ubah&id=<?= $r['idmargin_penjualan'] ?>">Ubah</a>
                <form method="post" onsubmit="return confirm('Hapus data ini?')">
                    <input type="hidden" name="aksi" value="hapus">
                    <input type="hidden" name="id" value="<?= $r['idmargin_penjualan'] ?>">
                    <button class="btn hapus" type="submit">Hapus</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php if (!$rows): ?><tr><td colspan="6" class="kosong">Belum ada data.</td></tr><?php endif; ?>
    </table>
<?php endif;
include 'footer.php';
