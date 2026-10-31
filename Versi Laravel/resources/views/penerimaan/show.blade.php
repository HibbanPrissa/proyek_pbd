@extends('layouts.app')

@section('judul', 'Detail Penerimaan #' . $p->idpenerimaan)
@section('subjudul', 'Barang yang diterima')

@section('tombol')
    <a class="btn-sekunder" href="{{ route('penerimaan.index') }}">Kembali</a>
@endsection

@section('isi')
<dl class="ringkas">
    <div><dt>Tanggal</dt><dd>{{ $p->tanggal }}</dd></div>
    <div><dt>Pengadaan</dt><dd>#{{ $p->idpengadaan }}</dd></div>
    <div><dt>Vendor</dt><dd>{{ $p->nama_vendor }}</dd></div>
    <div><dt>Diterima Oleh</dt><dd>{{ $p->username }}</dd></div>
    <div><dt>Status</dt><dd>@include('partials.badge', ['label' => $p->status_label])</dd></div>
</dl>

<div class="tabel-wrap">
    <table>
        <tr>
            <th class="c">No</th><th>Barang</th><th>Satuan</th>
            <th class="kanan">Jumlah Diterima</th><th class="kanan">Harga Satuan</th><th class="kanan">Subtotal</th>
        </tr>
        @foreach ($detail as $i => $d)
            <tr>
                <td class="c">{{ $i + 1 }}</td>
                <td>{{ $d->nama }}</td>
                <td>{{ $d->nama_satuan }}</td>
                <td class="kanan">{{ $d->jumlah_terima }}</td>
                <td class="kanan">Rp {{ number_format($d->harga_satuan_terima, 0, ',', '.') }}</td>
                <td class="kanan">Rp {{ number_format($d->sub_total_terima, 0, ',', '.') }}</td>
            </tr>
        @endforeach
    </table>
</div>
@endsection
