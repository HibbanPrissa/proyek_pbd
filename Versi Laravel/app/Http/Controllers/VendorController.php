<?php

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VendorController extends Controller
{
    private function aturan(): array
    {
        return [
            'nama_vendor' => 'required|max:100',
            'badan_hukum' => 'required|in:P,C,U',   // P = PT, C = CV, U = UD
            'status'      => 'required|in:A,N',     // A = aktif, N = nonaktif
        ];
    }

    public function index()
    {
        $rows = DB::table('v_vendor')->orderBy('idvendor')->get();
        return view('vendor.index', compact('rows'));
    }

    public function create()
    {
        return view('vendor.form', ['data' => null]);
    }

    public function store(Request $request)
    {
        $d = $request->validate($this->aturan(), $this->pesan);
        DB::table('vendor')->insert($d);

        return redirect()->route('vendor.index')->with('sukses', 'Vendor berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $data = DB::table('v_vendor')->where('idvendor', $id)->first();
        abort_if(! $data, 404);

        return view('vendor.form', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $d = $request->validate($this->aturan(), $this->pesan);
        DB::table('vendor')->where('idvendor', $id)->update($d);

        return redirect()->route('vendor.index')->with('sukses', 'Vendor berhasil diubah.');
    }

    public function destroy($id)
    {
        try {
            DB::table('vendor')->where('idvendor', $id)->delete();
        } catch (QueryException $e) {
            return redirect()->route('vendor.index')->with('galat', $this->pesanGalat($e));
        }

        return redirect()->route('vendor.index')->with('sukses', 'Vendor berhasil dihapus.');
    }
}
