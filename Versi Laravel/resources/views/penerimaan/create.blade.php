@extends('layouts.app')

@section('judul', 'Buat Penerimaan')
@section('subjudul', 'Terima barang dari pengadaan')

@section('isi')
@if (! $pengadaan)
    <form class="filter" method="get" action="{{ route('penerimaan.create') }}">
        <select name="idpengadaan" required>
            <option value="">-- pilih pengadaan yang berstatus Proses --</option>
            @foreach ($daftar as $p)
                <option value="{{ $p->idpengadaan }}">#{{ $p->idpengadaan }} - {{ $p->nama_vendor }} ({{ $p->tanggal }})</option>
            @endforeach
        </select>
        <button class="btn-primary" type="submit">Lanjut</button>
    </form>
    @if ($daftar->isEmpty())
        <p class="catatan">Tidak ada pengadaan yang menunggu penerimaan.</p>
    @endif
@else
    <dl class="ringkas">
        <div><dt>Pengadaan</dt><dd>#{{ $pengadaan->idpengadaan }}</dd></div>
        <div><dt>Vendor</dt><dd>{{ $pengadaan->nama_vendor }}</dd></div>
        <div><dt>Tanggal</dt><dd>{{ $pengadaan->tanggal }}</dd></div>
    </dl>

    <form class="isian lebar" method="post" action="{{ route('penerimaan.store') }}">
        @csrf
        <input type="hidden" name="idpengadaan" value="{{ $pengadaan->idpengadaan }}">

        <label>Diterima Oleh</label>
        <select name="iduser" required>
            <option value="">-- pilih user --</option>
            @foreach ($users as $u)
                <option value="{{ $u->iduser }}" {{ (string) old('iduser') === (string) $u->iduser ? 'selected' : '' }}>
                    {{ $u->username }} ({{ $u->nama_role }})
                </option>
            @endforeach
        </select>

        <label>Jumlah barang yang diterima</label>
        <table class="tabel-isian">
            <thead>
                <tr><th>Barang</th><th>Dipesan</th><th>Diterima</th></tr>
            </thead>
            <tbody>
                @foreach ($detail as $d)
                    <tr>
                        <td>{{ $d->nama }} ({{ $d->nama_satuan }})</td>
                        <td>{{ $d->jumlah }}</td>
                        <td>
                            <input type="number" name="terima[{{ $d->iddetail_pengadaan }}]"
                                   min="0" max="{{ $d->jumlah }}"
                                   value="{{ old('terima.' . $d->iddetail_pengadaan, $d->jumlah) }}" required>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <p class="catatan">Jumlah diterima boleh lebih kecil dari yang dipesan, tapi tidak boleh lebih besar.</p>

        <div class="tombol">
            <button class="btn-primary" type="submit">Simpan</button>
            <a class="btn-sekunder" href="{{ route('penerimaan.create') }}">Batal</a>
        </div>
    </form>
@endif
@endsection
