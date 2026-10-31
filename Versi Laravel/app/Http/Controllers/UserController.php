<?php

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    private function aturan(bool $baru): array
    {
        return [
            'username' => 'required|max:45',
            // saat tambah password wajib, saat ubah boleh kosong (tidak diganti)
            'password' => ($baru ? 'required' : 'nullable') . '|max:50',
            'idrole'   => 'required|integer|exists:role,idrole',
        ];
    }

    private function daftarRole()
    {
        return DB::table('v_role')->orderBy('nama_role')->get();   // dropdown dari VIEW
    }

    public function index()
    {
        $rows = DB::table('v_user')->orderBy('iduser')->get();
        return view('user.index', compact('rows'));
    }

    public function create()
    {
        return view('user.form', ['data' => null, 'roles' => $this->daftarRole()]);
    }

    public function store(Request $request)
    {
        $d = $request->validate($this->aturan(true), $this->pesan);

        // hash SHA-256, hasilnya sama dengan SHA2(password, 256) di MySQL
        $d['password'] = hash('sha256', $d['password']);
        DB::table('user')->insert($d);

        return redirect()->route('user.index')->with('sukses', 'User berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $data = DB::table('v_user')->where('iduser', $id)->first();
        abort_if(! $data, 404);

        return view('user.form', ['data' => $data, 'roles' => $this->daftarRole()]);
    }

    public function update(Request $request, $id)
    {
        $d = $request->validate($this->aturan(false), $this->pesan);

        if (! empty($d['password'])) {
            $d['password'] = hash('sha256', $d['password']);
        } else {
            unset($d['password']);   // dikosongkan = password lama tetap
        }

        DB::table('user')->where('iduser', $id)->update($d);

        return redirect()->route('user.index')->with('sukses', 'User berhasil diubah.');
    }

    public function destroy($id)
    {
        try {
            DB::table('user')->where('iduser', $id)->delete();
        } catch (QueryException $e) {
            return redirect()->route('user.index')->with('galat', $this->pesanGalat($e));
        }

        return redirect()->route('user.index')->with('sukses', 'User berhasil dihapus.');
    }
}
