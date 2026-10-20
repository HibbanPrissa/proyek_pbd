<?php
require 'koneksi.php';
$err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $aksi = $_POST['aksi'] ?? '';
        if ($aksi === 'tambah') {
            $st = $pdo->prepare("INSERT INTO role (nama_role) VALUES (?)");
            $st->execute([trim($_POST['nama_role'])]);
            redirect('role.php', 'Role berhasil ditambahkan.');
        } elseif ($aksi === 'ubah') {
            $st = $pdo->prepare("UPDATE role SET nama_role = ? WHERE idrole = ?");
            $st->execute([trim($_POST['nama_role']), (int)$_POST['id']]);
            redirect('role.php', 'Role berhasil diubah.');
        } elseif ($aksi === 'hapus') {
            $st = $pdo->prepare("DELETE FROM role WHERE idrole = ?");
            $st->execute([(int)$_POST['id']]);
            redirect('role.php', 'Role berhasil dihapus.');
        }
    } catch (PDOException $ex) {
        $err = pesanError($ex);
    }
}

$mode = $_GET['mode'] ?? 'list';
$data = null;
if ($mode === 'ubah') {
    $st = $pdo->prepare("SELECT * FROM v_role WHERE idrole = ?");
    $st->execute([(int)($_GET['id'] ?? 0)]);
    $data = $st->fetch();
    if (!$data) $mode = 'list';
}

$form = ($mode === 'tambah' || $mode === 'ubah');
$judul = $form ? (($mode === 'tambah' ? 'Tambah' : 'Ubah') . ' Role') : 'Data Role';
$subjudul = $form ? '' : 'Master data hak akses pengguna';
$tombol = $form ? null : ['role.php?mode=tambah', 'Tambah Role'];
include 'header.php';

if ($mode === 'tambah' || $mode === 'ubah'): ?>
    <form class="isian" method="post">
        <input type="hidden" name="aksi" value="<?= $mode ?>">
        <input type="hidden" name="id" value="<?= (int)($data['idrole'] ?? 0) ?>">

        <label>Nama Role</label>
        <input type="text" name="nama_role" maxlength="100" required value="<?= nilai('nama_role', $data) ?>">

        <div class="tombol">
            <button class="btn-primary" type="submit">Simpan</button>
            <a class="btn-sekunder" href="role.php">Batal</a>
        </div>
    </form>
<?php else:
    $rows = $pdo->query("SELECT * FROM v_role ORDER BY idrole")->fetchAll(); ?>
    <div class="tabel-wrap">
    <table>
        <tr><th class="c">No</th><th>Nama Role</th><th class="kanan">Aksi</th></tr>
        <?php foreach ($rows as $i => $r): ?>
        <tr>
            <td><?= $i + 1 ?></td>
            <td><?= e($r['nama_role']) ?></td>
            <td class="aksi kanan">
                <a class="btn-ubah" href="role.php?mode=ubah&id=<?= $r['idrole'] ?>">Ubah</a>
                <form method="post" class="form-hapus">
                    <input type="hidden" name="aksi" value="hapus">
                    <input type="hidden" name="id" value="<?= $r['idrole'] ?>">
                    <button class="btn-hapus" type="submit">Hapus</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php if (!$rows): ?><tr><td colspan="3" class="kosong">Belum ada data.</td></tr><?php endif; ?>
    </table>
    </div>
<?php endif;
include 'footer.php';
