<?php
$host = 'localhost';
$db   = 'proyek_pbd';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    die('Koneksi database gagal: ' . $e->getMessage());
}

define('PENGGUNA_NAMA', 'Hibban');
define('PENGGUNA_ROLE', 'Admin');

function e($s) {
    return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
}

function redirect($url, $msg) {
    header('Location: ' . $url . '?msg=' . urlencode($msg));
    exit;
}

function nilai($key, $data, $default = '') {
    if (isset($_POST[$key])) return e($_POST[$key]);
    if (is_array($data) && isset($data[$key])) return e($data[$key]);
    return e($default);
}

function pesanError(PDOException $ex) {
    $kode = $ex->errorInfo[1] ?? 0;
    if ($kode == 1451) return 'Data tidak bisa dihapus karena masih dipakai di tabel lain.';
    if ($kode == 1452) return 'Data referensi (relasi) tidak valid.';
    if ($kode == 1062) return 'Data duplikat.';
    return 'Terjadi kesalahan: ' . $ex->getMessage();
}

function badge($label) {
    $kelas = ($label === 'Aktif') ? 'aktif' : 'nonaktif';
    return '<span class="badge ' . $kelas . '">' . e($label) . '</span>';
}
