@extends('layouts.app')

@section('judul', 'Buat Penjualan')
@section('subjudul', 'Catat penjualan ke pelanggan')

@section('isi')
@php
    $items = old('items', [['idbarang' => '', 'jumlah' => 1]]);
@endphp
<dl class="ringkas">
    <div>
        <dt>Margin Aktif</dt>
        <dd>{{ rtrim(rtrim(number_format($margin->persen, 2, ',', '.'), '0'), ',') }}%</dd>
    </div>
</dl>

<form class="isian lebar" method="post" action="{{ route('penjualan.store') }}">
    @csrf

    <label>Kasir</label>
    <select name="iduser" required>
        <option value="">-- pilih user --</option>
        @foreach ($users as $u)
            <option value="{{ $u->iduser }}" {{ (string) old('iduser') === (string) $u->iduser ? 'selected' : '' }}>
                {{ $u->username }} ({{ $u->nama_role }})
            </option>
        @endforeach
    </select>

    <label>Barang yang dijual</label>
    <table class="tabel-isian">
        <thead>
            <tr><th>Barang (harga jual dan stok)</th><th>Jumlah</th><th></th></tr>
        </thead>
        <tbody id="baris-item">
            @foreach ($items as $n => $it)
                @include('penjualan._baris', ['n' => $n, 'it' => $it, 'barang' => $barang])
            @endforeach
        </tbody>
    </table>
    <button type="button" class="btn-sekunder" id="tambah-baris">+ Tambah Barang</button>

    <p class="catatan">Harga jual = harga barang + margin aktif. Subtotal, PPN 11%, dan total dihitung otomatis saat disimpan.</p>

    <div class="tombol">
        <button class="btn-primary" type="submit">Simpan</button>
        <a class="btn-sekunder" href="{{ route('penjualan.index') }}">Batal</a>
    </div>
</form>

<template id="baris-baru">
    @include('penjualan._baris', ['n' => '__N__', 'it' => [], 'barang' => $barang])
</template>

<script>
(function () {
    var tbody = document.getElementById('baris-item');
    var tpl = document.getElementById('baris-baru');
    var n = {{ count($items) }};

    document.getElementById('tambah-baris').addEventListener('click', function () {
        tbody.insertAdjacentHTML('beforeend', tpl.innerHTML.replace(/__N__/g, n++));
    });

    tbody.addEventListener('click', function (ev) {
        if (ev.target.classList.contains('hapus-baris') && tbody.rows.length > 1) {
            ev.target.closest('tr').remove();
        }
    });
})();
</script>
@endsection
