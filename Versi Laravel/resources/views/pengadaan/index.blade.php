@extends('layouts.app')

@section('judul', 'Pengadaan')
@section('subjudul', 'Pemesanan barang ke vendor')

@section('tombol')
    <a class="btn-primary" href="{{ route('pengadaan.create') }}">
        <span class="material-symbols-outlined">add</span>Buat Pengadaan
    </a>
@endsection

@section('isi')
<div class="tabel-wrap">
    <table>
        <tr>
            <th class="c">No</th><th>Tanggal</th><th>Vendor</th><th>Dibuat Oleh</th>
            <th class="c">Status</th><th class="kanan">Total</th><th class="kanan">Aksi</th>
        </tr>
        @forelse ($rows as $i => $r)
            <tr>
                <td class="c">{{ $i + 1 }}</td>
                <td>{{ $r->tanggal }}</td>
                <td>{{ $r->nama_vendor }}</td>
                <td>{{ $r->username }}</td>
                <td class="c">@include('partials.badge', ['label' => $r->status_label])</td>
                <td class="kanan">Rp {{ number_format($r->total_nilai, 0, ',', '.') }}</td>
                <td class="aksi kanan">
                    <a class="btn-ubah" href="{{ route('pengadaan.show', $r->idpengadaan) }}">Lihat</a>
                </td>
            </tr>
        @empty
            <tr><td colspan="7" class="kosong">Belum ada pengadaan.</td></tr>
        @endforelse
    </table>
</div>
@endsection
