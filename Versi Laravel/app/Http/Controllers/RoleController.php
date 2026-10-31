<?php

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    private function aturan(): array
    {
        return ['nama_role' => 'required|max:100'];
    }

    public function index()
    {
        $rows = DB::table('v_role')->orderBy('idrole')->get();   // baca dari VIEW
        return view('role.index', compact('rows'));
    }

    public function create()
    {
        return view('role.form', ['data' => null]);
    }

    public function store(Request $request)
    {
        $d = $request->validate($this->aturan(), $this->pesan);
        DB::table('role')->insert($d);                            // tulis ke TABEL

        return redirect()->route('role.index')->with('sukses', 'Role berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $data = DB::table('v_role')->where('idrole', $id)->first();
        abort_if(! $data, 404);

        return view('role.form', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $d = $request->validate($this->aturan(), $this->pesan);
        DB::table('role')->where('idrole', $id)->update($d);

        return redirect()->route('role.index')->with('sukses', 'Role berhasil diubah.');
    }

    public function destroy($id)
    {
        try {
            DB::table('role')->where('idrole', $id)->delete();
        } catch (QueryException $e) {
            return redirect()->route('role.index')->with('galat', $this->pesanGalat($e));
        }

        return redirect()->route('role.index')->with('sukses', 'Role berhasil dihapus.');
    }
}
