<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReturController extends Controller
{
    // Stok terkini = saldo pada baris kartu_stok terbaru milik barang itu.
    // Nanti bisa diganti FUNCTION fn_stok_barang().
    private function stokBarang($idbarang): int
    {
        return (int) (DB::table('kartu_stok')->where('idbarang', $idbarang)
            ->orderByDesc('idkartu_stok')->value('stock') ?? 0);
    }

    // Jumlah yang sudah diretur untuk satu baris penerimaan
    private function sudahRetur($iddetailPenerimaan): int
    {
        return (int) DB::table('detail_retur')->where('iddetail_penerimaan', $iddetailPenerimaan)->sum('jumlah');
    }

    public function index()
    {
        $rows = DB::table('v_retur')->orderByDesc('idretur')->get();
        return view('retur.index', compact('rows'));
    }

    public function create(Request $request)
    {
        $idpenerimaan = $request->query('idpenerimaan');
        $penerimaan = null;
        $detail = collect();

        if ($idpenerimaan) {
            $penerimaan = DB::table('v_penerimaan')->where('idpenerimaan', $idpenerimaan)->first();
            if (! $penerimaan) {
                return redirect()->route('retur.create')->with('galat', 'Penerimaan tidak ditemukan.');
            }

            $detail = DB::table('v_detail_penerimaan')
                ->where('idpenerimaan', $idpenerimaan)->orderBy('iddetail_penerimaan')->get();
            foreach ($detail as $d) {
                $d->sudah_retur = $this->sudahRetur($d->iddetail_penerimaan);
                $d->sisa        = $d->jumlah_terima - $d->sudah_retur;
            }
        }

        return view('retur.create', [
            'penerimaan' => $penerimaan,
            'detail'     => $detail,
            'daftar'     => DB::table('v_penerimaan')->orderByDesc('idpenerimaan')->get(),
            'users'      => DB::table('v_user')->orderBy('username')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $d = $request->validate([
            'idpenerimaan' => 'required|integer|exists:penerimaan,idpenerimaan',
            'iduser'       => 'required|integer|exists:user,iduser',
            'jumlah'       => 'required|array',
            'jumlah.*'     => 'required|integer|min:0',
            'alasan'       => 'nullable|array',
            'alasan.*'     => 'nullable|max:200',
        ], $this->pesan, [
            'idpenerimaan' => 'Penerimaan',
            'iduser'       => 'Dibuat oleh',
            'jumlah'       => 'Jumlah retur',
            'jumlah.*'     => 'Jumlah retur',
            'alasan.*'     => 'Alasan',
        ]);

        $baris = [];
        $dipakai = [];   // jumlah retur per barang, untuk cek stok kumulatif
        $detail = DB::table('detail_penerimaan')->where('idpenerimaan', $d['idpenerimaan'])->get();

        foreach ($detail as $det) {
            $jml = (int) ($d['jumlah'][$det->iddetail_penerimaan] ?? 0);
            if ($jml < 1) {
                continue;
            }

            $alasan = trim($d['alasan'][$det->iddetail_penerimaan] ?? '');
            if ($alasan === '') {
                return back()->withInput()->with('galat', 'Alasan retur wajib diisi untuk barang yang diretur.');
            }

            // BAGIAN A - jumlah retur tidak boleh melebihi jumlah diterima.
            // Nanti bisa diganti TRIGGER trg_cek_retur.
            if ($this->sudahRetur($det->iddetail_penerimaan) + $jml > $det->jumlah_terima) {
                return back()->withInput()->with('galat', 'Jumlah retur melebihi jumlah barang yang diterima.');
            }

            // stok tidak boleh menjadi minus
            $dipakai[$det->barang_idbarang] = ($dipakai[$det->barang_idbarang] ?? 0) + $jml;
            $stok = $this->stokBarang($det->barang_idbarang);
            if ($stok < $dipakai[$det->barang_idbarang]) {
                return back()->withInput()->with('galat', "Stok tidak cukup untuk diretur (tersisa {$stok}).");
            }

            $baris[] = [$det, $jml, $alasan];
        }

        if (! $baris) {
            return back()->withInput()->with('galat', 'Isi jumlah retur minimal untuk satu barang.');
        }

        $id = DB::transaction(function () use ($d, $baris) {
            $id = DB::table('retur')->insertGetId([
                'idpenerimaan' => $d['idpenerimaan'],
                'iduser'       => $d['iduser'],
            ]);

            foreach ($baris as [$det, $jml, $alasan]) {
                DB::table('detail_retur')->insert([
                    'jumlah'              => $jml,
                    'alasan'              => $alasan,
                    'idretur'             => $id,
                    'iddetail_penerimaan' => $det->iddetail_penerimaan,
                ]);

                // BAGIAN B - kartu stok diisi dari PHP (barang keluar).
                // Nanti bisa diganti TRIGGER trg_stok_retur (hapus bagian ini kalau dipakai).
                DB::table('kartu_stok')->insert([
                    'jenis_transaksi' => 'R',          // R = Retur
                    'masuk'           => 0,
                    'keluar'          => $jml,
                    'stock'           => $this->stokBarang($det->barang_idbarang) - $jml,
                    'idtransaksi'     => $id,
                    'idbarang'        => $det->barang_idbarang,
                ]);
            }

            return $id;
        });

        return redirect()->route('retur.show', $id)
            ->with('sukses', 'Retur berhasil disimpan dan stok berkurang.');
    }

    public function show($id)
    {
        $r = DB::table('v_retur')->where('idretur', $id)->first();
        abort_if(! $r, 404);

        return view('retur.show', [
            'r'      => $r,
            'detail' => DB::table('v_detail_retur')->where('idretur', $id)->orderBy('iddetail_retur')->get(),
        ]);
    }
}
