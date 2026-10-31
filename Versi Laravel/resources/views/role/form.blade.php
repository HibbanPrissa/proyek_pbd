@extends('layouts.app')

@section('judul', $data ? 'Ubah Role' : 'Tambah Role')

@section('isi')
<form class="isian" method="post" action="{{ $data ? route('role.update', $data->idrole) : route('role.store') }}">
    @csrf
    @if ($data) @method('PUT') @endif

    <label>Nama Role</label>
    <input type="text" name="nama_role" maxlength="100" required value="{{ old('nama_role', $data->nama_role ?? '') }}">

    <div class="tombol">
        <button class="btn-primary" type="submit">Simpan</button>
        <a class="btn-sekunder" href="{{ route('role.index') }}">Batal</a>
    </div>
</form>
@endsection
