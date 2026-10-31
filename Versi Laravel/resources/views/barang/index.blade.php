@extends('layouts.app')

@section('judul', 'Data Barang')
@section('subjudul', 'Master data inventaris dan katalog barang toko')

@section('tombol')
    <a class="btn-primary" href="{{ route('barang.create') }}">
        <span class="material-symbols-outlined">add</span>Tambah Barang
    </a>
@endsection

@section('isi')
<div class="tabel-wrap">
    <table>
        <tr><th class="c">No</th><th>Nama</th><th>Jenis</th><th>Satuan</th><th>Harga</th><th class="c">Status</th><th class="kanan">Aksi</th></tr>
        @forelse ($rows as $i => $r)
            <tr>
                <td class="c">{{ $i + 1 }}</td>
                <td>{{ $r->nama }}</td>
                <td>{{ $r->jenis_label }}</td>
                <td>{{ $r->nama_satuan }}</td>
                <td>Rp {{ number_format($r->harga, 0, ',', '.') }}</td>
                <td class="c">@include('partials.badge', ['label' => $r->status_label])</td>
                <td class="aksi kanan">
                    <a class="btn-ubah" href="{{ route('barang.edit', $r->idbarang) }}">Ubah</a>
                    <form method="post" action="{{ route('barang.destroy', $r->idbarang) }}" class="form-hapus">
                        @csrf
                        @method('DELETE')
                        <button class="btn-hapus" type="submit">Hapus</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="7" class="kosong">Belum ada data.</td></tr>
        @endforelse
    </table>
</div>
@endsection
