<?php

use App\Http\Controllers\BarangController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MarginPenjualanController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SatuanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VendorController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PengadaanController;
use App\Http\Controllers\PenerimaanController;
use App\Http\Controllers\KartuStokController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\ReturController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('role', RoleController::class)->except('show');
Route::resource('user', UserController::class)->except('show');
Route::resource('satuan', SatuanController::class)->except('show');
Route::resource('vendor', VendorController::class)->except('show');
Route::resource('barang', BarangController::class)->except('show');
Route::resource('margin_penjualan', MarginPenjualanController::class)->except('show');
Route::resource('pengadaan', PengadaanController::class)->only(['index', 'create', 'store', 'show']);
Route::resource('penerimaan', PenerimaanController::class)->only(['index', 'create', 'store', 'show']);
Route::get('kartu_stok', [KartuStokController::class, 'index'])->name('kartu_stok.index');
Route::resource('penjualan', PenjualanController::class)->only(['index', 'create', 'store', 'show']);
Route::resource('retur', ReturController::class)->only(['index', 'create', 'store', 'show']);
