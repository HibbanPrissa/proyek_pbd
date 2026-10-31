@extends('layouts.app')

@section('judul', 'Detail Penjualan #' . $p->idpenjualan)
@section('subjudul', 'Rincian barang yang dijual')

@section('tombol')
    <a class="btn-sekunder" href="{{ route('penjualan.index') }}">Kembali</a>
@endsection

@section('isi')
<dl class="ringkas">
    <div><dt>Tanggal</dt><dd>{{ $p->tanggal }}</dd></div>
    <div><dt>Kasir</dt><dd>{{ $p->username }}</dd></div>
    <div><dt>Margin</dt><dd>{{ rtrim(rtrim(number_format($p->margin_persen, 2, ',', '.'), '0'), ',') }}%</dd></div>
</dl>

<div class="tabel-wrap">
    <table>
        <tr>
            <th class="c">No</th><th>Barang</th><th>Satuan</th>
            <th class="kanan">Harga Satuan</th><th class="kanan">Jumlah</th><th class="kanan">Subtotal</th>
        </tr>
        @foreach ($detail as $i => $d)
            <tr>
                <td class="c">{{ $i + 1 }}</td>
                <td>{{ $d->nama }}</td>
                <td>{{ $d->nama_satuan }}</td>
                <td class="kanan">Rp {{ number_format($d->harga_satuan, 0, ',', '.') }}</td>
                <td class="kanan">{{ $d->jumlah }}</td>
                <td class="kanan">Rp {{ number_format($d->subtotal, 0, ',', '.') }}</td>
            </tr>
        @endforeach
    </table>
</div>

<div class="total-box">
    <div><span>Subtotal</span><span>Rp {{ number_format($p->subtotal_nilai, 0, ',', '.') }}</span></div>
    <div><span>PPN</span><span>Rp {{ number_format($p->ppn, 0, ',', '.') }}</span></div>
    <div class="total"><span>Total</span><span>Rp {{ number_format($p->total_nilai, 0, ',', '.') }}</span></div>
</div>
@endsection
