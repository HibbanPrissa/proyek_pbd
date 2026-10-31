@extends('layouts.app')

@section('judul', $data ? 'Ubah Vendor' : 'Tambah Vendor')

@section('isi')
@php
    $bhNow = old('badan_hukum', $data->badan_hukum ?? 'P');
    $stNow = old('status', $data->status ?? 'A');
@endphp
<form class="isian" method="post" action="{{ $data ? route('vendor.update', $data->idvendor) : route('vendor.store') }}">
    @csrf
    @if ($data) @method('PUT') @endif

    <label>Nama Vendor</label>
    <input type="text" name="nama_vendor" maxlength="100" required value="{{ old('nama_vendor', $data->nama_vendor ?? '') }}">

    <label>Badan Hukum</label>
    <select name="badan_hukum">
        <option value="P" {{ $bhNow === 'P' ? 'selected' : '' }}>PT</option>
        <option value="C" {{ $bhNow === 'C' ? 'selected' : '' }}>CV</option>
        <option value="U" {{ $bhNow === 'U' ? 'selected' : '' }}>UD</option>
    </select>

    <label>Status</label>
    <select name="status">
        <option value="A" {{ $stNow === 'A' ? 'selected' : '' }}>Aktif</option>
        <option value="N" {{ $stNow === 'N' ? 'selected' : '' }}>Nonaktif</option>
    </select>

    <div class="tombol">
        <button class="btn-primary" type="submit">Simpan</button>
        <a class="btn-sekunder" href="{{ route('vendor.index') }}">Batal</a>
    </div>
</form>
@endsection
