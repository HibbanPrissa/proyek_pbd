@extends('layouts.app')

@section('judul', 'Data User')
@section('subjudul', 'Master data pengguna aplikasi')

@section('tombol')
    <a class="btn-primary" href="{{ route('user.create') }}">
        <span class="material-symbols-outlined">add</span>Tambah User
    </a>
@endsection

@section('isi')
<div class="tabel-wrap">
    <table>
        <tr><th class="c">No</th><th>Username</th><th>Role</th><th class="kanan">Aksi</th></tr>
        @forelse ($rows as $i => $r)
            <tr>
                <td class="c">{{ $i + 1 }}</td>
                <td>{{ $r->username }}</td>
                <td>{{ $r->nama_role }}</td>
                <td class="aksi kanan">
                    <a class="btn-ubah" href="{{ route('user.edit', $r->iduser) }}">Ubah</a>
                    <form method="post" action="{{ route('user.destroy', $r->iduser) }}" class="form-hapus">
                        @csrf
                        @method('DELETE')
                        <button class="btn-hapus" type="submit">Hapus</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="4" class="kosong">Belum ada data.</td></tr>
        @endforelse
    </table>
</div>
@endsection
