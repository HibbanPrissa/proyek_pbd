<?php
require 'koneksi.php';
$err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $aksi = $_POST['aksi'] ?? '';
        if ($aksi === 'tambah') {
            $st = $pdo->prepare("INSERT INTO barang (jenis, nama, idsatuan, status, harga) VALUES (?, ?, ?, ?, ?)");
            $st->execute([$_POST['jenis'], trim($_POST['nama']), (int)$_POST['idsatuan'],
                          (int)$_POST['status'], (int)$_POST['harga']]);
            redirect('barang.php', 'Barang berhasil ditambahkan.');
        } elseif ($aksi === 'ubah') {
            $st = $pdo->prepare("UPDATE barang SET jenis = ?, nama = ?, idsatuan = ?, status = ?, harga = ? WHERE idbarang = ?");
            $st->execute([$_POST['jenis'], trim($_POST['nama']), (int)$_POST['idsatuan'],
                          (int)$_POST['status'], (int)$_POST['harga'], (int)$_POST['id']]);
            redirect('barang.php', 'Barang berhasil diubah.');
        } elseif ($aksi === 'hapus') {
            $st = $pdo->prepare("DELETE FROM barang WHERE idbarang = ?");
            $st->execute([(int)$_POST['id']]);
            redirect('barang.php', 'Barang berhasil dihapus.');
        }
    } catch (PDOException $ex) {
        $err = pesanError($ex);
    }
}

$mode = $_GET['mode'] ?? 'list';
$data = null;
if ($mode === 'ubah') {
    $st = $pdo->prepare("SELECT * FROM v_barang WHERE idbarang = ?");
    $st->execute([(int)($_GET['id'] ?? 0)]);
    $data = $st->fetch();
    if (!$data) $mode = 'list';
}

$judul = 'Data Barang';
include 'header.php';

if ($mode === 'tambah' || $mode === 'ubah'):
    $jenisNow  = $_POST['jenis']    ?? ($data['jenis'] ?? 'M');
    $satuanNow = $_POST['idsatuan'] ?? ($data['idsatuan'] ?? '');
    $statusNow = $_POST['status']   ?? ($data['status'] ?? 1);
    // dropdown satuan diambil dari VIEW (hanya yang aktif, atau yang sedang dipakai barang ini)
    $stSat = $pdo->prepare("SELECT idsatuan, nama_satuan FROM v_satuan WHERE status = 1 OR idsatuan = ? ORDER BY nama_satuan");
    $stSat->execute([(int)$satuanNow]);
    $satuan = $stSat->fetchAll(); ?>
    <form class="isian" method="post">
        <input type="hidden" name="aksi" value="<?= $mode ?>">
        <input type="hidden" name="id" value="<?= (int)($data['idbarang'] ?? 0) ?>">

        <label>Nama Barang</label>
        <input type="text" name="nama" maxlength="45" required value="<?= nilai('nama', $data) ?>">

        <label>Jenis</label>
        <select name="jenis">
            <option value="M" <?= $jenisNow === 'M' ? 'selected' : '' ?>>Makanan</option>
            <option value="N" <?= $jenisNow === 'N' ? 'selected' : '' ?>>Non Makanan</option>
        </select>

        <label>Satuan</label>
        <select name="idsatuan" required>
            <option value="">-- pilih satuan --</option>
            <?php foreach ($satuan as $s): ?>
                <option value="<?= $s['idsatuan'] ?>" <?= $satuanNow == $s['idsatuan'] ? 'selected' : '' ?>>
                    <?= e($s['nama_satuan']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Harga (Rp)</label>
        <input type="number" name="harga" min="0" required value="<?= nilai('harga', $data) ?>">

        <label>Status</label>
        <select name="status">
            <option value="1" <?= $statusNow == 1 ? 'selected' : '' ?>>Aktif</option>
            <option value="0" <?= $statusNow == 0 ? 'selected' : '' ?>>Nonaktif</option>
        </select>

        <div class="tombol">
            <button class="btn" type="submit">Simpan</button>
            <a class="btn batal" href="barang.php">Batal</a>
        </div>
    </form>
<?php else:
    $rows = $pdo->query("SELECT * FROM v_barang ORDER BY idbarang")->fetchAll(); ?>
    <div class="toolbar"><a class="btn" href="barang.php?mode=tambah">+ Tambah Barang</a></div>
    <table>
        <tr><th>No</th><th>Nama</th><th>Jenis</th><th>Satuan</th><th>Harga</th><th>Status</th><th>Aksi</th></tr>
        <?php foreach ($rows as $i => $r): ?>
        <tr>
            <td><?= $i + 1 ?></td>
            <td><?= e($r['nama']) ?></td>
            <td><?= e($r['jenis_label']) ?></td>
            <td><?= e($r['nama_satuan']) ?></td>
            <td>Rp <?= number_format($r['harga'], 0, ',', '.') ?></td>
            <td><?= e($r['status_label']) ?></td>
            <td class="aksi">
                <a class="btn edit" href="barang.php?mode=ubah&id=<?= $r['idbarang'] ?>">Ubah</a>
                <form method="post" onsubmit="return confirm('Hapus data ini?')">
                    <input type="hidden" name="aksi" value="hapus">
                    <input type="hidden" name="id" value="<?= $r['idbarang'] ?>">
                    <button class="btn hapus" type="submit">Hapus</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php if (!$rows): ?><tr><td colspan="7" class="kosong">Belum ada data.</td></tr><?php endif; ?>
    </table>
<?php endif;
include 'footer.php';
