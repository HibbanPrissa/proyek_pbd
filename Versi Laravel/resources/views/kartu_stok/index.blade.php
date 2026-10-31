@extends('layouts.app')

@section('judul', 'Kartu Stok')
@section('subjudul', 'Riwayat barang masuk dan keluar')

@section('isi')
<form class="filter" method="get" action="{{ route('kartu_stok.index') }}">
    <select name="idbarang">
        <option value="">Semua barang</option>
        @foreach ($barang as $b)
            <option value="{{ $b->idbarang }}" {{ (string) $idbarang === (string) $b->idbarang ? 'selected' : '' }}>
                {{ $b->nama }}
            </option>
        @endforeach
    </select>
    <button class="btn-primary" type="submit">Tampilkan</button>
</form>

<div class="tabel-wrap">
    <table>
        <tr>
            <th class="c">No</th><th>Waktu</th><th>Barang</th><th>Jenis</th>
            <th class="kanan">Masuk</th><th class="kanan">Keluar</th><th class="kanan">Stok</th>
        </tr>
        @forelse ($rows as $i => $r)
            <tr>
                <td class="c">{{ $i + 1 }}</td>
                <td>{{ $r->created_at }}</td>
                <td>{{ $r->nama }}</td>
                <td>{{ $r->jenis_label }}</td>
                <td class="kanan">{{ $r->masuk }}</td>
                <td class="kanan">{{ $r->keluar }}</td>
                <td class="kanan">{{ $r->stock }}</td>
            </tr>
        @empty
            <tr><td colspan="7" class="kosong">Belum ada riwayat stok.</td></tr>
        @endforelse
    </table>
</div>
@endsection
