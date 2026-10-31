<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // jumlah data dihitung dari VIEW
        $daftar = [
            ['nama' => 'Role',   'view' => 'v_role',             'rute' => 'role',             'ikon' => 'admin_panel_settings'],
            ['nama' => 'User',   'view' => 'v_user',             'rute' => 'user',             'ikon' => 'manage_accounts'],
            ['nama' => 'Satuan', 'view' => 'v_satuan',           'rute' => 'satuan',           'ikon' => 'straighten'],
            ['nama' => 'Vendor', 'view' => 'v_vendor',           'rute' => 'vendor',           'ikon' => 'store'],
            ['nama' => 'Barang', 'view' => 'v_barang',           'rute' => 'barang',           'ikon' => 'inventory_2'],
            ['nama' => 'Margin', 'view' => 'v_margin_penjualan', 'rute' => 'margin_penjualan', 'ikon' => 'price_change'],
        ];

        foreach ($daftar as $i => $d) {
            $daftar[$i]['jumlah'] = DB::table($d['view'])->count();
        }

        return view('dashboard', compact('daftar'));
    }
}
