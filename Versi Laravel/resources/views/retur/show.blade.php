@extends('layouts.app')

@section('judul', 'Detail Retur #' . $r->idretur)
@section('subjudul', 'Barang yang dikembalikan')

@section('tombol')
    <a class="btn-sekunder" href="{{ route('retur.index') }}">Kembali</a>
@endsection

@section('isi')
<dl class="ringkas">
    <div><dt>Tanggal</dt><dd>{{ $r->tanggal }}</dd></div>
    <div><dt>Penerimaan</dt><dd>#{{ $r->idpenerimaan }}</dd></div>
    <div><dt>Vendor</dt><dd>{{ $r->nama_vendor }}</dd></div>
    <div><dt>Dibuat Oleh</dt><dd>{{ $r->username }}</dd></div>
</dl>

<div class="tabel-wrap">
    <table>
        <tr>
            <th class="c">No</th><th>Barang</th><th>Satuan</th>
            <th class="kanan">Jumlah</th><th>Alasan</th>
        </tr>
        @foreach ($detail as $i => $d)
            <tr>
                <td class="c">{{ $i + 1 }}</td>
                <td>{{ $d->nama }}</td>
                <td>{{ $d->nama_satuan }}</td>
                <td class="kanan">{{ $d->jumlah }}</td>
                <td>{{ $d->alasan }}</td>
            </tr>
        @endforeach
    </table>
</div>
@endsection
