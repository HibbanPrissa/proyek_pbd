<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenerimaanController extends Controller
{
    public function index()
    {
        $rows = DB::table('v_penerimaan')->orderByDesc('idpenerimaan')->get();
        return view('penerimaan.index', compact('rows'));
    }

    public function create(Request $request)
    {
        $idpengadaan = $request->query('idpengadaan');
        $pengadaan = null;
        $detail = collect();

        if ($idpengadaan) {
            $pengadaan = DB::table('v_pengadaan')
                ->where('idpengadaan', $idpengadaan)->where('status', 'P')->first();

            if (! $pengadaan) {
                return redirect()->route('penerimaan.create')
                    ->with('galat', 'Pengadaan tidak ditemukan atau sudah diterima.');
            }

            $detail = DB::table('v_detail_pengadaan')
                ->where('idpengadaan', $idpengadaan)->orderBy('iddetail_pengadaan')->get();
        }

        return view('penerimaan.create', [
            'pengadaan' => $pengadaan,
            'detail'    => $detail,
            'daftar'    => DB::table('v_pengadaan')->where('status', 'P')->orderBy('idpengadaan')->get(),
            'users'     => DB::table('v_user')->orderBy('username')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $d = $request->validate([
            'idpengadaan' => 'required|integer|exists:pengadaan,idpengadaan',
            'iduser'      => 'required|integer|exists:user,iduser',
            'terima'      => 'required|array',
            'terima.*'    => 'required|integer|min:0',
        ], $this->pesan, [
            'idpengadaan' => 'Pengadaan',
            'iduser'      => 'Penerima',
            'terima'      => 'Jumlah diterima',
            'terima.*'    => 'Jumlah diterima',
        ]);

        $pengadaan = DB::table('pengadaan')->where('idpengadaan', $d['idpengadaan'])->first();
        if ($pengadaan->status !== 'P') {
            return redirect()->route('penerimaan.create')
                ->with('galat', 'Pengadaan ini sudah diterima atau dibatalkan.');
        }

        // jumlah diterima per barang tidak boleh melebihi jumlah yang dipesan
        $baris = [];
        $detail = DB::table('detail_pengadaan')->where('idpengadaan', $d['idpengadaan'])->get();
        foreach ($detail as $det) {
            $jml = (int) ($d['terima'][$det->iddetail_pengadaan] ?? 0);
            if ($jml > $det->jumlah) {
                return back()->withInput()->with('galat', 'Jumlah diterima tidak boleh melebihi jumlah yang dipesan.');
            }
            if ($jml > 0) {
                $baris[] = [$det, $jml];
            }
        }
        if (! $baris) {
            return back()->withInput()->with('galat', 'Isi jumlah diterima minimal untuk satu barang.');
        }

        $idterima = DB::transaction(function () use ($d, $baris) {
            $idterima = DB::table('penerimaan')->insertGetId([
                'status'      => 'S',                  // S = Selesai
                'idpengadaan' => $d['idpengadaan'],
                'iduser'      => $d['iduser'],
            ]);

            foreach ($baris as [$det, $jml]) {
                DB::table('detail_penerimaan')->insert([
                    'idpenerimaan'        => $idterima,
                    'barang_idbarang'     => $det->idbarang,
                    'jumlah_terima'       => $jml,
                    'harga_satuan_terima' => $det->harga_satuan,
                    'sub_total_terima'    => $jml * $det->harga_satuan,
                ]);

                // BAGIAN A - kartu stok diisi dari PHP.
                // Nanti bisa diganti TRIGGER trg_stok_penerimaan (hapus bagian ini kalau dipakai).
                $stokLama = DB::table('kartu_stok')->where('idbarang', $det->idbarang)
                    ->orderByDesc('idkartu_stok')->value('stock') ?? 0;
                DB::table('kartu_stok')->insert([
                    'jenis_transaksi' => 'P',          // P = Penerimaan
                    'masuk'           => $jml,
                    'keluar'          => 0,
                    'stock'           => $stokLama + $jml,
                    'idtransaksi'     => $idterima,
                    'idbarang'        => $det->idbarang,
                ]);
            }

            // pengadaan yang sudah diterima berstatus S = Selesai
            DB::table('pengadaan')->where('idpengadaan', $d['idpengadaan'])->update(['status' => 'S']);

            return $idterima;
        });

        return redirect()->route('penerimaan.show', $idterima)
            ->with('sukses', 'Penerimaan berhasil disimpan dan kartu stok diperbarui.');
    }

    public function show($id)
    {
        $p = DB::table('v_penerimaan')->where('idpenerimaan', $id)->first();
        abort_if(! $p, 404);

        return view('penerimaan.show', [
            'p'      => $p,
            'detail' => DB::table('v_detail_penerimaan')->where('idpenerimaan', $id)->orderBy('iddetail_penerimaan')->get(),
        ]);
    }
}
