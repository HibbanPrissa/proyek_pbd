<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenjualanController extends Controller
{
    // Stok terkini = saldo pada baris kartu_stok terbaru milik barang itu.
    // Nanti bisa diganti FUNCTION fn_stok_barang().
    private function stokBarang($idbarang): int
    {
        return (int) (DB::table('kartu_stok')->where('idbarang', $idbarang)
            ->orderByDesc('idkartu_stok')->value('stock') ?? 0);
    }

    public function index()
    {
        $rows = DB::table('v_penjualan')->orderByDesc('idpenjualan')->get();
        return view('penjualan.index', compact('rows'));
    }

    public function create()
    {
        $margin = DB::table('v_margin_penjualan')->where('status', 1)->first();
        if (! $margin) {
            return redirect()->route('penjualan.index')
                ->with('galat', 'Belum ada margin aktif. Aktifkan satu margin di menu Margin Penjualan.');
        }

        // harga jual = harga barang + margin aktif; stok dihitung dari kartu stok
        $barang = DB::table('v_barang')->where('status', 1)->orderBy('nama')->get();
        foreach ($barang as $b) {
            $b->harga_jual = (int) round($b->harga * (1 + $margin->persen / 100));
            $b->stok       = $this->stokBarang($b->idbarang);
        }

        return view('penjualan.create', [
            'margin' => $margin,
            'barang' => $barang,
            'users'  => DB::table('v_user')->orderBy('username')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $d = $request->validate([
            'iduser'           => 'required|integer|exists:user,iduser',
            'items'            => 'required|array|min:1',
            'items.*.idbarang' => 'required|integer|exists:barang,idbarang',
            'items.*.jumlah'   => 'required|integer|min:1',
        ], $this->pesan, [
            'iduser'           => 'Kasir',
            'items'            => 'Daftar barang',
            'items.*.idbarang' => 'Barang',
            'items.*.jumlah'   => 'Jumlah',
        ]);

        $margin = DB::table('margin_penjualan')->where('status', 1)->first();
        if (! $margin) {
            return back()->withInput()->with('galat', 'Belum ada margin aktif.');
        }

        // total jumlah per barang (barang yang sama boleh muncul di beberapa baris)
        $perBarang = [];
        foreach ($d['items'] as $item) {
            $perBarang[$item['idbarang']] = ($perBarang[$item['idbarang']] ?? 0) + $item['jumlah'];
        }
        $dataBarang = DB::table('barang')->whereIn('idbarang', array_keys($perBarang))->get()->keyBy('idbarang');

        // BAGIAN A - cek stok dari PHP.
        // Nanti bisa diganti TRIGGER trg_cek_stok_penjualan.
        foreach ($perBarang as $idbarang => $jumlah) {
            $stok = $this->stokBarang($idbarang);
            if ($stok < $jumlah) {
                return back()->withInput()
                    ->with('galat', "Stok {$dataBarang[$idbarang]->nama} tidak cukup (tersisa {$stok}).");
            }
        }

        $id = DB::transaction(function () use ($d, $margin, $dataBarang) {
            // harga satuan = harga barang + margin aktif; subtotal = harga x jumlah
            $baris = [];
            $subtotal = 0;
            foreach ($d['items'] as $item) {
                $harga = (int) round($dataBarang[$item['idbarang']]->harga * (1 + $margin->persen / 100));
                $sub   = $harga * $item['jumlah'];
                $subtotal += $sub;
                $baris[] = [$item['idbarang'], $item['jumlah'], $harga, $sub];
            }

            // BAGIAN B - total dihitung di PHP (PPN 11%).
            // Nanti bisa diganti PROCEDURE sp_hitung_total_penjualan / FUNCTION fn_hitung_ppn.
            $ppn = (int) round($subtotal * 0.11);

            $id = DB::table('penjualan')->insertGetId([
                'subtotal_nilai'     => $subtotal,
                'ppn'                => $ppn,
                'total_nilai'        => $subtotal + $ppn,
                'iduser'             => $d['iduser'],
                'idmargin_penjualan' => $margin->idmargin_penjualan,
            ]);

            foreach ($baris as [$idbarang, $jumlah, $harga, $sub]) {
                DB::table('detail_penjualan')->insert([
                    'harga_satuan'          => $harga,
                    'jumlah'                => $jumlah,
                    'subtotal'              => $sub,
                    'penjualan_idpenjualan' => $id,
                    'idbarang'              => $idbarang,
                ]);

                // BAGIAN C - kartu stok diisi dari PHP (barang keluar).
                // Nanti bisa diganti TRIGGER trg_stok_penjualan (hapus bagian ini kalau dipakai).
                DB::table('kartu_stok')->insert([
                    'jenis_transaksi' => 'J',          // J = Penjualan
                    'masuk'           => 0,
                    'keluar'          => $jumlah,
                    'stock'           => $this->stokBarang($idbarang) - $jumlah,
                    'idtransaksi'     => $id,
                    'idbarang'        => $idbarang,
                ]);
            }

            return $id;
        });

        return redirect()->route('penjualan.show', $id)
            ->with('sukses', 'Penjualan berhasil disimpan dan stok berkurang.');
    }

    public function show($id)
    {
        $p = DB::table('v_penjualan')->where('idpenjualan', $id)->first();
        abort_if(! $p, 404);

        return view('penjualan.show', [
            'p'      => $p,
            'detail' => DB::table('v_detail_penjualan')->where('idpenjualan', $id)->orderBy('iddetail_penjualan')->get(),
        ]);
    }
}
