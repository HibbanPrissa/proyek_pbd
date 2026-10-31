@extends('layouts.app')

@section('judul', 'Penerimaan')
@section('subjudul', 'Penerimaan barang dari vendor')

@section('tombol')
    <a class="btn-primary" href="{{ route('penerimaan.create') }}">
        <span class="material-symbols-outlined">add</span>Buat Penerimaan
    </a>
@endsection

@section('isi')
<div class="tabel-wrap">
    <table>
        <tr>
            <th class="c">No</th><th>Tanggal</th><th>Pengadaan</th><th>Vendor</th>
            <th>Diterima Oleh</th><th class="c">Status</th><th class="kanan">Aksi</th>
        </tr>
        @forelse ($rows as $i => $r)
            <tr>
                <td class="c">{{ $i + 1 }}</td>
                <td>{{ $r->tanggal }}</td>
                <td>#{{ $r->idpengadaan }}</td>
                <td>{{ $r->nama_vendor }}</td>
                <td>{{ $r->username }}</td>
                <td class="c">@include('partials.badge', ['label' => $r->status_label])</td>
                <td class="aksi kanan">
                    <a class="btn-ubah" href="{{ route('penerimaan.show', $r->idpenerimaan) }}">Lihat</a>
                </td>
            </tr>
        @empty
            <tr><td colspan="7" class="kosong">Belum ada penerimaan.</td></tr>
        @endforelse
    </table>
</div>
@endsection
