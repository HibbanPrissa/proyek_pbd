@extends('layouts.app')

@section('judul', 'Retur')
@section('subjudul', 'Pengembalian barang ke vendor')

@section('tombol')
    <a class="btn-primary" href="{{ route('retur.create') }}">
        <span class="material-symbols-outlined">add</span>Buat Retur
    </a>
@endsection

@section('isi')
<div class="tabel-wrap">
    <table>
        <tr>
            <th class="c">No</th><th>Tanggal</th><th>Penerimaan</th><th>Vendor</th>
            <th>Dibuat Oleh</th><th class="kanan">Aksi</th>
        </tr>
        @forelse ($rows as $i => $r)
            <tr>
                <td class="c">{{ $i + 1 }}</td>
                <td>{{ $r->tanggal }}</td>
                <td>#{{ $r->idpenerimaan }}</td>
                <td>{{ $r->nama_vendor }}</td>
                <td>{{ $r->username }}</td>
                <td class="aksi kanan">
                    <a class="btn-ubah" href="{{ route('retur.show', $r->idretur) }}">Lihat</a>
                </td>
            </tr>
        @empty
            <tr><td colspan="6" class="kosong">Belum ada retur.</td></tr>
        @endforelse
    </table>
</div>
@endsection
