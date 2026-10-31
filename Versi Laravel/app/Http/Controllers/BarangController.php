<?php

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BarangController extends Controller
{
    private function aturan(): array
    {
        return [
            'nama'     => 'required|max:45',
            'jenis'    => 'required|in:M,N',        // M = makanan, N = non makanan
            'idsatuan' => 'required|integer|exists:satuan,idsatuan',
            'harga'    => 'required|integer|min:0',
            'status'   => 'required|in:0,1',
        ];
    }

    // Dropdown satuan dari VIEW: hanya yang aktif, ditambah satuan yang sedang dipakai barang ini
    private function daftarSatuan($idSaatIni = null)
    {
        return DB::table('v_satuan')
            ->where('status', 1)
            ->orWhere('idsatuan', $idSaatIni)
            ->orderBy('nama_satuan')
            ->get();
    }

    public function index()
    {
        $rows = DB::table('v_barang')->orderBy('idbarang')->get();
        return view('barang.index', compact('rows'));
    }

    public function create()
    {
        return view('barang.form', ['data' => null, 'satuan' => $this->daftarSatuan()]);
    }

    public function store(Request $request)
    {
        $d = $request->validate($this->aturan(), $this->pesan);
        DB::table('barang')->insert($d);

        return redirect()->route('barang.index')->with('sukses', 'Barang berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $data = DB::table('v_barang')->where('idbarang', $id)->first();
        abort_if(! $data, 404);

        return view('barang.form', ['data' => $data, 'satuan' => $this->daftarSatuan($data->idsatuan)]);
    }

    public function update(Request $request, $id)
    {
        $d = $request->validate($this->aturan(), $this->pesan);
        DB::table('barang')->where('idbarang', $id)->update($d);

        return redirect()->route('barang.index')->with('sukses', 'Barang berhasil diubah.');
    }

    public function destroy($id)
    {
        try {
            DB::table('barang')->where('idbarang', $id)->delete();
        } catch (QueryException $e) {
            return redirect()->route('barang.index')->with('galat', $this->pesanGalat($e));
        }

        return redirect()->route('barang.index')->with('sukses', 'Barang berhasil dihapus.');
    }
}
