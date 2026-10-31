@extends('layouts.app')

@section('judul', $data ? 'Ubah User' : 'Tambah User')

@section('isi')
<form class="isian" method="post" action="{{ $data ? route('user.update', $data->iduser) : route('user.store') }}">
    @csrf
    @if ($data) @method('PUT') @endif

    <label>Username</label>
    <input type="text" name="username" maxlength="45" required value="{{ old('username', $data->username ?? '') }}">

    <label>Password @if ($data) (kosongkan jika tidak ingin mengganti) @endif</label>
    <input type="password" name="password" maxlength="50" {{ $data ? '' : 'required' }}>

    <label>Role</label>
    <select name="idrole" required>
        <option value="">-- pilih role --</option>
        @foreach ($roles as $r)
            <option value="{{ $r->idrole }}" {{ (string) old('idrole', $data->idrole ?? '') === (string) $r->idrole ? 'selected' : '' }}>
                {{ $r->nama_role }}
            </option>
        @endforeach
    </select>

    <div class="tombol">
        <button class="btn-primary" type="submit">Simpan</button>
        <a class="btn-sekunder" href="{{ route('user.index') }}">Batal</a>
    </div>
</form>
@endsection
