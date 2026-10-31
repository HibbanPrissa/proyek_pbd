@extends('layouts.app')

@section('judul', 'Data Role')
@section('subjudul', 'Master data hak akses pengguna')

@section('tombol')
    <a class="btn-primary" href="{{ route('role.create') }}">
        <span class="material-symbols-outlined">add</span>Tambah Role
    </a>
@endsection

@section('isi')
<div class="tabel-wrap">
    <table>
        <tr><th class="c">No</th><th>Nama Role</th><th class="kanan">Aksi</th></tr>
        @forelse ($rows as $i => $r)
            <tr>
                <td class="c">{{ $i + 1 }}</td>
                <td>{{ $r->nama_role }}</td>
                <td class="aksi kanan">
                    <a class="btn-ubah" href="{{ route('role.edit', $r->idrole) }}">Ubah</a>
                    <form method="post" action="{{ route('role.destroy', $r->idrole) }}" class="form-hapus">
                        @csrf
                        @method('DELETE')
                        <button class="btn-hapus" type="submit">Hapus</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="3" class="kosong">Belum ada data.</td></tr>
        @endforelse
    </table>
</div>
@endsection
