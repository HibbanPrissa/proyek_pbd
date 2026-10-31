<?php

use App\Http\Controllers\BarangController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MarginPenjualanController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SatuanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VendorController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Route::resource otomatis membuat 6 alamat untuk tiap tabel:
//   index (daftar), create (form tambah), store (simpan),
//   edit (form ubah), update (simpan perubahan), destroy (hapus)
Route::resource('role', RoleController::class)->except('show');
Route::resource('user', UserController::class)->except('show');
Route::resource('satuan', SatuanController::class)->except('show');
Route::resource('vendor', VendorController::class)->except('show');
Route::resource('barang', BarangController::class)->except('show');
Route::resource('margin_penjualan', MarginPenjualanController::class)->except('show');
