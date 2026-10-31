@extends('layouts.app')

@section('judul', $data ? 'Ubah Satuan' : 'Tambah Satuan')

@section('isi')
@php $statusNow = old('status', $data->status ?? 1); @endphp
<form class="isian" method="post" action="{{ $data ? route('satuan.update', $data->idsatuan) : route('satuan.store') }}">
    @csrf
    @if ($data) @method('PUT') @endif

    <label>Nama Satuan</label>
    <input type="text" name="nama_satuan" maxlength="45" required value="{{ old('nama_satuan', $data->nama_satuan ?? '') }}">

    <label>Status</label>
    <select name="status">
        <option value="1" {{ $statusNow == 1 ? 'selected' : '' }}>Aktif</option>
        <option value="0" {{ $statusNow == 0 ? 'selected' : '' }}>Nonaktif</option>
    </select>

    <div class="tombol">
        <button class="btn-primary" type="submit">Simpan</button>
        <a class="btn-sekunder" href="{{ route('satuan.index') }}">Batal</a>
    </div>
</form>
@endsection
