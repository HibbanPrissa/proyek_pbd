<?php
require 'koneksi.php';
$err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $aksi = $_POST['aksi'] ?? '';
        if ($aksi === 'tambah') {
            $st = $pdo->prepare("INSERT INTO `user` (username, password, idrole) VALUES (?, SHA2(?, 256), ?)");
            $st->execute([trim($_POST['username']), $_POST['password'], (int)$_POST['idrole']]);
            redirect('user.php', 'User berhasil ditambahkan.');
        } elseif ($aksi === 'ubah') {
            if ($_POST['password'] !== '') {
                $st = $pdo->prepare("UPDATE `user` SET username = ?, password = SHA2(?, 256), idrole = ? WHERE iduser = ?");
                $st->execute([trim($_POST['username']), $_POST['password'], (int)$_POST['idrole'], (int)$_POST['id']]);
            } else {
                $st = $pdo->prepare("UPDATE `user` SET username = ?, idrole = ? WHERE iduser = ?");
                $st->execute([trim($_POST['username']), (int)$_POST['idrole'], (int)$_POST['id']]);
            }
            redirect('user.php', 'User berhasil diubah.');
        } elseif ($aksi === 'hapus') {
            $st = $pdo->prepare("DELETE FROM `user` WHERE iduser = ?");
            $st->execute([(int)$_POST['id']]);
            redirect('user.php', 'User berhasil dihapus.');
        }
    } catch (PDOException $ex) {
        $err = pesanError($ex);
    }
}

$mode = $_GET['mode'] ?? 'list';
$data = null;
if ($mode === 'ubah') {
    $st = $pdo->prepare("SELECT * FROM v_user WHERE iduser = ?");
    $st->execute([(int)($_GET['id'] ?? 0)]);
    $data = $st->fetch();
    if (!$data) $mode = 'list';
}

$form = ($mode === 'tambah' || $mode === 'ubah');
$judul = $form ? (($mode === 'tambah' ? 'Tambah' : 'Ubah') . ' User') : 'Data User';
$subjudul = $form ? '' : 'Master data pengguna aplikasi';
$tombol = $form ? null : ['user.php?mode=tambah', 'Tambah User'];
include 'header.php';

if ($mode === 'tambah' || $mode === 'ubah'):
    $roleNow = $_POST['idrole'] ?? ($data['idrole'] ?? '');
    $roles = $pdo->query("SELECT idrole, nama_role FROM v_role ORDER BY nama_role")->fetchAll(); ?>
    <form class="isian" method="post">
        <input type="hidden" name="aksi" value="<?= $mode ?>">
        <input type="hidden" name="id" value="<?= (int)($data['iduser'] ?? 0) ?>">

        <label>Username</label>
        <input type="text" name="username" maxlength="45" required value="<?= nilai('username', $data) ?>">

        <label>Password <?= $mode === 'ubah' ? '(kosongkan jika tidak diganti)' : '' ?></label>
        <input type="password" name="password" maxlength="50" <?= $mode === 'tambah' ? 'required' : '' ?>>

        <label>Role</label>
        <select name="idrole" required>
            <option value="">-- pilih role --</option>
            <?php foreach ($roles as $r): ?>
                <option value="<?= $r['idrole'] ?>" <?= $roleNow == $r['idrole'] ? 'selected' : '' ?>>
                    <?= e($r['nama_role']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <div class="tombol">
            <button class="btn-primary" type="submit">Simpan</button>
            <a class="btn-sekunder" href="user.php">Batal</a>
        </div>
    </form>
<?php else:
    $rows = $pdo->query("SELECT * FROM v_user ORDER BY iduser")->fetchAll(); ?>
    <div class="tabel-wrap">
    <table>
        <tr><th class="c">No</th><th>Username</th><th>Role</th><th class="kanan">Aksi</th></tr>
        <?php foreach ($rows as $i => $r): ?>
        <tr>
            <td><?= $i + 1 ?></td>
            <td><?= e($r['username']) ?></td>
            <td><?= e($r['nama_role']) ?></td>
            <td class="aksi kanan">
                <a class="btn-ubah" href="user.php?mode=ubah&id=<?= $r['iduser'] ?>">Ubah</a>
                <form method="post" class="form-hapus">
                    <input type="hidden" name="aksi" value="hapus">
                    <input type="hidden" name="id" value="<?= $r['iduser'] ?>">
                    <button class="btn-hapus" type="submit">Hapus</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php if (!$rows): ?><tr><td colspan="4" class="kosong">Belum ada data.</td></tr><?php endif; ?>
    </table>
    </div>
<?php endif;
include 'footer.php';
