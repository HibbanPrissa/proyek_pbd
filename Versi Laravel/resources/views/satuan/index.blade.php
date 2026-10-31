@extends('layouts.app')

@section('judul', 'Data Satuan')
@section('subjudul', 'Master data satuan barang')

@section('tombol')
    <a class="btn-primary" href="{{ route('satuan.create') }}">
        <span class="material-symbols-outlined">add</span>Tambah Satuan
    </a>
@endsection

@section('isi')
<div class="tabel-wrap">
    <table>
        <tr><th class="c">No</th><th>Nama Satuan</th><th class="c">Status</th><th class="kanan">Aksi</th></tr>
        @forelse ($rows as $i => $r)
            <tr>
                <td class="c">{{ $i + 1 }}</td>
                <td>{{ $r->nama_satuan }}</td>
                <td class="c">@include('partials.badge', ['label' => $r->status_label])</td>
                <td class="aksi kanan">
                    <a class="btn-ubah" href="{{ route('satuan.edit', $r->idsatuan) }}">Ubah</a>
                    <form method="post" action="{{ route('satuan.destroy', $r->idsatuan) }}" class="form-hapus">
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
