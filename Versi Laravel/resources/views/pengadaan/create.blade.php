@extends('layouts.app')

@section('judul', 'Buat Pengadaan')
@section('subjudul', 'Pesan barang ke vendor')

@section('isi')
@php
    $items = old('items', [['idbarang' => '', 'jumlah' => 1, 'harga_satuan' => '']]);
@endphp
<form class="isian lebar" method="post" action="{{ route('pengadaan.store') }}">
    @csrf

    <label>Vendor</label>
    <select name="vendor_idvendor" required>
        <option value="">-- pilih vendor --</option>
        @foreach ($vendors as $v)
            <option value="{{ $v->idvendor }}" {{ (string) old('vendor_idvendor') === (string) $v->idvendor ? 'selected' : '' }}>
                {{ $v->nama_vendor }}
            </option>
        @endforeach
    </select>

    <label>Dibuat Oleh</label>
    <select name="user_iduser" required>
        <option value="">-- pilih user --</option>
        @foreach ($users as $u)
            <option value="{{ $u->iduser }}" {{ (string) old('user_iduser') === (string) $u->iduser ? 'selected' : '' }}>
                {{ $u->username }} ({{ $u->nama_role }})
            </option>
        @endforeach
    </select>

    <label>Barang yang dipesan</label>
    <table class="tabel-isian">
        <thead>
            <tr><th>Barang</th><th>Jumlah</th><th>Harga Satuan (Rp)</th><th></th></tr>
        </thead>
        <tbody id="baris-item">
            @foreach ($items as $n => $it)
                @include('pengadaan._baris', ['n' => $n, 'it' => $it, 'barang' => $barang])
            @endforeach
        </tbody>
    </table>
    <button type="button" class="btn-sekunder" id="tambah-baris">+ Tambah Barang</button>

    <p class="catatan">Subtotal, PPN 11%, dan total dihitung otomatis saat disimpan.</p>

    <div class="tombol">
        <button class="btn-primary" type="submit">Simpan</button>
        <a class="btn-sekunder" href="{{ route('pengadaan.index') }}">Batal</a>
    </div>
</form>

<template id="baris-baru">
    @include('pengadaan._baris', ['n' => '__N__', 'it' => [], 'barang' => $barang])
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

    // pilih barang -> harga satuan terisi otomatis (boleh diubah)
    tbody.addEventListener('change', function (ev) {
        if (ev.target.classList.contains('pilih-barang')) {
            var opsi = ev.target.options[ev.target.selectedIndex];
            var harga = ev.target.closest('tr').querySelector('.isi-harga');
            if (opsi && opsi.dataset.harga && harga.value === '') { harga.value = opsi.dataset.harga; }
        }
    });
})();
</script>
@endsection
