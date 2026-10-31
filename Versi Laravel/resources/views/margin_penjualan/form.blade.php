@extends('layouts.app')

@section('judul', $data ? 'Ubah Margin Penjualan' : 'Tambah Margin Penjualan')

@section('isi')
@php
    $userNow   = old('iduser', $data->iduser ?? '');
    $statusNow = old('status', $data->status ?? 1);
@endphp
<form class="isian" method="post"
      action="{{ $data ? route('margin_penjualan.update', $data->idmargin_penjualan) : route('margin_penjualan.store') }}">
    @csrf
    @if ($data) @method('PUT') @endif

    <label>Persen Margin (%)</label>
    <input type="number" name="persen" min="0" step="0.01" required value="{{ old('persen', $data->persen ?? '') }}">

    <label>Status</label>
    <select name="status">
        <option value="1" {{ $statusNow == 1 ? 'selected' : '' }}>Aktif</option>
        <option value="0" {{ $statusNow == 0 ? 'selected' : '' }}>Nonaktif</option>
    </select>

    <label>Dibuat Oleh</label>
    <select name="iduser" required>
        <option value="">-- pilih user --</option>
        @foreach ($users as $u)
            <option value="{{ $u->iduser }}" {{ (string) $userNow === (string) $u->iduser ? 'selected' : '' }}>
                {{ $u->username }}
            </option>
        @endforeach
    </select>

    <div class="tombol">
        <button class="btn-primary" type="submit">Simpan</button>
        <a class="btn-sekunder" href="{{ route('margin_penjualan.index') }}">Batal</a>
    </div>
</form>
@endsection
