<?php

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;

abstract class Controller
{
    // Pesan validasi dalam bahasa Indonesia, dipakai semua controller
    protected array $pesan = [
        'required' => ':attribute wajib diisi.',
        'max'      => ':attribute maksimal :max karakter.',
        'min'      => ':attribute minimal :min.',
        'in'       => ':attribute tidak valid.',
        'integer'  => ':attribute harus berupa angka bulat.',
        'numeric'  => ':attribute harus berupa angka.',
        'exists'   => ':attribute tidak ditemukan.',
        'array'    => ':attribute tidak valid.',
    ];

    // Mengubah error MySQL menjadi pesan yang mudah dipahami
    protected function pesanGalat(QueryException $e): string
    {
        $kode = $e->errorInfo[1] ?? 0;

        return match ($kode) {
            1451    => 'Data tidak bisa dihapus karena masih dipakai di tabel lain.',
            1452    => 'Data referensi (relasi) tidak valid.',
            1062    => 'Data duplikat.',
            default => 'Terjadi kesalahan pada database.',
        };
    }
}
