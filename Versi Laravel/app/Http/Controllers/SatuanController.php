<?php

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SatuanController extends Controller
{
    private function aturan(): array
    {
        return [
            'nama_satuan' => 'required|max:45',
            'status'      => 'required|in:0,1',
        ];
    }

    public function index()
    {
        $rows = DB::table('v_satuan')->orderBy('idsatuan')->get();
        return view('satuan.index', compact('rows'));
    }

    public function create()
    {
        return view('satuan.form', ['data' => null]);
    }

    public function store(Request $request)
    {
        $d = $request->validate($this->aturan(), $this->pesan);
        DB::table('satuan')->insert($d);

        return redirect()->route('satuan.index')->with('sukses', 'Satuan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $data = DB::table('v_satuan')->where('idsatuan', $id)->first();
        abort_if(! $data, 404);

        return view('satuan.form', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $d = $request->validate($this->aturan(), $this->pesan);
        DB::table('satuan')->where('idsatuan', $id)->update($d);

        return redirect()->route('satuan.index')->with('sukses', 'Satuan berhasil diubah.');
    }

    public function destroy($id)
    {
        try {
            DB::table('satuan')->where('idsatuan', $id)->delete();
        } catch (QueryException $e) {
            return redirect()->route('satuan.index')->with('galat', $this->pesanGalat($e));
        }

        return redirect()->route('satuan.index')->with('sukses', 'Satuan berhasil dihapus.');
    }
}
