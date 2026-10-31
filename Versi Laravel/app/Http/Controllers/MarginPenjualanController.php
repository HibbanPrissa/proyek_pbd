<?php

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MarginPenjualanController extends Controller
{
    private function aturan(): array
    {
        return [
            'persen' => 'required|numeric|min:0',
            'status' => 'required|in:0,1',
            'iduser' => 'required|integer|exists:user,iduser',
        ];
    }

    private function daftarUser()
    {
        return DB::table('v_user')->orderBy('username')->get();   // dropdown dari VIEW
    }

    public function index()
    {
        $rows = DB::table('v_margin_penjualan')->orderBy('idmargin_penjualan')->get();
        return view('margin_penjualan.index', compact('rows'));
    }

    public function create()
    {
        return view('margin_penjualan.form', ['data' => null, 'users' => $this->daftarUser()]);
    }

    public function store(Request $request)
    {
        $d = $request->validate($this->aturan(), $this->pesan);
        DB::table('margin_penjualan')->insert($d);

        return redirect()->route('margin_penjualan.index')->with('sukses', 'Margin berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $data = DB::table('v_margin_penjualan')->where('idmargin_penjualan', $id)->first();
        abort_if(! $data, 404);

        return view('margin_penjualan.form', ['data' => $data, 'users' => $this->daftarUser()]);
    }

    public function update(Request $request, $id)
    {
        $d = $request->validate($this->aturan(), $this->pesan);
        DB::table('margin_penjualan')->where('idmargin_penjualan', $id)->update($d);

        return redirect()->route('margin_penjualan.index')->with('sukses', 'Margin berhasil diubah.');
    }

    public function destroy($id)
    {
        try {
            DB::table('margin_penjualan')->where('idmargin_penjualan', $id)->delete();
        } catch (QueryException $e) {
            return redirect()->route('margin_penjualan.index')->with('galat', $this->pesanGalat($e));
        }

        return redirect()->route('margin_penjualan.index')->with('sukses', 'Margin berhasil dihapus.');
    }
}
