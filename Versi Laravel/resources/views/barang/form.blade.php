@extends('layouts.app')

@section('judul', $data ? 'Ubah Barang' : 'Tambah Barang')

@section('isi')
@php
    $jenisNow  = old('jenis', $data->jenis ?? 'M');
    $satuanNow = old('idsatuan', $data->idsatuan ?? '');
    $statusNow = old('status', $data->status ?? 1);
@endphp
<form class="isian" method="post" action="{{ $data ? route('barang.update', $data->idbarang) : route('barang.store') }}">
    @csrf
    @if ($data) @method('PUT') @endif

    <label>Nama Barang</label>
    <input type="text" name="nama" maxlength="45" required value="{{ old('nama', $data->nama ?? '') }}">

    <label>Jenis</label>
    <select name="jenis">
        <option value="M" {{ $jenisNow === 'M' ? 'selected' : '' }}>Makanan</option>
        <option value="N" {{ $jenisNow === 'N' ? 'selected' : '' }}>Non Makanan</option>
    </select>

    <label>Satuan</label>
    <select name="idsatuan" required>
        <option value="">-- pilih satuan --</option>
        @foreach ($satuan as $s)
            <option value="{{ $s->idsatuan }}" {{ (string) $satuanNow === (string) $s->idsatuan ? 'selected' : '' }}>
                {{ $s->nama_satuan }}
            </option>
        @endforeach
    </select>

    <label>Harga (Rp)</label>
    <input type="number" name="harga" min="0" required value="{{ old('harga', $data->harga ?? '') }}">

    <label>Status</label>
    <select name="status">
        <option value="1" {{ $statusNow == 1 ? 'selected' : '' }}>Aktif</option>
        <option value="0" {{ $statusNow == 0 ? 'selected' : '' }}>Nonaktif</option>
    </select>

    <div class="tombol">
        <button class="btn-primary" type="submit">Simpan</button>
        <a class="btn-sekunder" href="{{ route('barang.index') }}">Batal</a>
    </div>
</form>
@endsection
