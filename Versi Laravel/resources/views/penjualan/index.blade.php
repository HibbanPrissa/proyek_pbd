@extends('layouts.app')

@section('judul', 'Penjualan')
@section('subjudul', 'Transaksi penjualan ke pelanggan')

@section('tombol')
    <a class="btn-primary" href="{{ route('penjualan.create') }}">
        <span class="material-symbols-outlined">add</span>Buat Penjualan
    </a>
@endsection

@section('isi')
<div class="tabel-wrap">
    <table>
        <tr>
            <th class="c">No</th><th>Tanggal</th><th>Kasir</th><th>Margin</th>
            <th class="kanan">Total</th><th class="kanan">Aksi</th>
        </tr>
        @forelse ($rows as $i => $r)
            <tr>
                <td class="c">{{ $i + 1 }}</td>
                <td>{{ $r->tanggal }}</td>
                <td>{{ $r->username }}</td>
                <td>{{ rtrim(rtrim(number_format($r->margin_persen, 2, ',', '.'), '0'), ',') }}%</td>
                <td class="kanan">Rp {{ number_format($r->total_nilai, 0, ',', '.') }}</td>
                <td class="aksi kanan">
                    <a class="btn-ubah" href="{{ route('penjualan.show', $r->idpenjualan) }}">Lihat</a>
                </td>
            </tr>
        @empty
            <tr><td colspan="6" class="kosong">Belum ada penjualan.</td></tr>
        @endforelse
    </table>
</div>
@endsection
