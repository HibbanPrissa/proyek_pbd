<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengadaanController extends Controller
{
    public function index()
    {
        $rows = DB::table('v_pengadaan')->orderByDesc('idpengadaan')->get();
        return view('pengadaan.index', compact('rows'));
    }

    public function create()
    {
        return view('pengadaan.create', [
            'vendors' => DB::table('v_vendor')->where('status', 'A')->orderBy('nama_vendor')->get(),
            'users'   => DB::table('v_user')->orderBy('username')->get(),
            'barang'  => DB::table('v_barang')->where('status', 1)->orderBy('nama')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $d = $request->validate([
            'vendor_idvendor'      => 'required|integer|exists:vendor,idvendor',
            'user_iduser'          => 'required|integer|exists:user,iduser',
            'items'                => 'required|array|min:1',
            'items.*.idbarang'     => 'required|integer|exists:barang,idbarang',
            'items.*.jumlah'       => 'required|integer|min:1',
            'items.*.harga_satuan' => 'required|integer|min:0',
        ], $this->pesan, [
            'vendor_idvendor'      => 'Vendor',
            'user_iduser'          => 'Dibuat oleh',
            'items'                => 'Daftar barang',
            'items.*.idbarang'     => 'Barang',
            'items.*.jumlah'       => 'Jumlah',
            'items.*.harga_satuan' => 'Harga satuan',
        ]);

        $id = DB::transaction(function () use ($d) {
            // 1) data pengadaan (status P = Proses)
            $id = DB::table('pengadaan')->insertGetId([
                'user_iduser'     => $d['user_iduser'],
                'status'          => 'P',
                'vendor_idvendor' => $d['vendor_idvendor'],
            ]);

            // 2) tiap barang: subtotal = harga x jumlah, dihitung di PHP
            $subtotal = 0;
            foreach ($d['items'] as $item) {
                $sub = $item['harga_satuan'] * $item['jumlah'];
                $subtotal += $sub;

                DB::table('detail_pengadaan')->insert([
                    'harga_satuan' => $item['harga_satuan'],
                    'jumlah'       => $item['jumlah'],
                    'sub_total'    => $sub,
                    'idbarang'     => $item['idbarang'],
                    'idpengadaan'  => $id,
                ]);
            }

            // 3) total pengadaan: PPN 11% dihitung di PHP
            $ppn = (int) round($subtotal * 0.11);
            DB::table('pengadaan')->where('idpengadaan', $id)->update([
                'subtotal_nilai' => $subtotal,
                'ppn'            => $ppn,
                'total_nilai'    => $subtotal + $ppn,
            ]);

            return $id;
        });

        return redirect()->route('pengadaan.show', $id)->with('sukses', 'Pengadaan berhasil disimpan.');
    }

    public function show($id)
    {
        $p = DB::table('v_pengadaan')->where('idpengadaan', $id)->first();
        abort_if(! $p, 404);

        return view('pengadaan.show', [
            'p'      => $p,
            'detail' => DB::table('v_detail_pengadaan')->where('idpengadaan', $id)->orderBy('iddetail_pengadaan')->get(),
        ]);
    }
}
