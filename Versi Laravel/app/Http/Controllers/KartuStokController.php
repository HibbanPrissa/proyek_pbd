<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KartuStokController extends Controller
{
    public function index(Request $request)
    {
        $idbarang = $request->query('idbarang');

        $query = DB::table('v_kartu_stok');
        if ($idbarang) {
            $query->where('idbarang', $idbarang)->orderBy('idkartu_stok');   // satu barang: urut waktu
        } else {
            $query->orderByDesc('idkartu_stok');                              // semua: terbaru di atas
        }

        return view('kartu_stok.index', [
            'rows'     => $query->get(),
            'barang'   => DB::table('v_barang')->orderBy('nama')->get(),
            'idbarang' => $idbarang,
        ]);
    }
}
