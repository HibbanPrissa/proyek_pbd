@extends('layouts.app')

@section('judul', 'Detail Pengadaan #' . $p->idpengadaan)
@section('subjudul', 'Rincian barang yang dipesan')

@section('tombol')
    @if ($p->status === 'P')
        <a class="btn-primary" href="{{ route('penerimaan.create', ['idpengadaan' => $p->idpengadaan]) }}">Terima Barang</a>
    @endif
    <a class="btn-sekunder" href="{{ route('pengadaan.index') }}">Kembali</a>
@endsection

@section('isi')
<dl class="ringkas">
    <div><dt>Tanggal</dt><dd>{{ $p->tanggal }}</dd></div>
    <div><dt>Vendor</dt><dd>{{ $p->nama_vendor }}</dd></div>
    <div><dt>Dibuat Oleh</dt><dd>{{ $p->username }}</dd></div>
    <div><dt>Status</dt><dd>@include('partials.badge', ['label' => $p->status_label])</dd></div>
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
                <td class="kanan">Rp {{ number_format($d->sub_total, 0, ',', '.') }}</td>
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
