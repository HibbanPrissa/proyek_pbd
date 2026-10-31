@extends('layouts.app')

@section('judul', 'Data Vendor')
@section('subjudul', 'Master data pemasok barang')

@section('tombol')
    <a class="btn-primary" href="{{ route('vendor.create') }}">
        <span class="material-symbols-outlined">add</span>Tambah Vendor
    </a>
@endsection

@section('isi')
<div class="tabel-wrap">
    <table>
        <tr><th class="c">No</th><th>Nama Vendor</th><th>Badan Hukum</th><th class="c">Status</th><th class="kanan">Aksi</th></tr>
        @forelse ($rows as $i => $r)
            <tr>
                <td class="c">{{ $i + 1 }}</td>
                <td>{{ $r->nama_vendor }}</td>
                <td>{{ $r->badan_hukum_label }}</td>
                <td class="c">@include('partials.badge', ['label' => $r->status_label])</td>
                <td class="aksi kanan">
                    <a class="btn-ubah" href="{{ route('vendor.edit', $r->idvendor) }}">Ubah</a>
                    <form method="post" action="{{ route('vendor.destroy', $r->idvendor) }}" class="form-hapus">
                        @csrf
                        @method('DELETE')
                        <button class="btn-hapus" type="submit">Hapus</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="5" class="kosong">Belum ada data.</td></tr>
        @endforelse
    </table>
</div>
@endsection
