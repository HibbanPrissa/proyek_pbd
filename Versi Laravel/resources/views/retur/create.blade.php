@extends('layouts.app')

@section('judul', 'Buat Retur')
@section('subjudul', 'Kembalikan barang yang sudah diterima')

@section('isi')
@if (! $penerimaan)
    <form class="filter" method="get" action="{{ route('retur.create') }}">
        <select name="idpenerimaan" required>
            <option value="">-- pilih penerimaan --</option>
            @foreach ($daftar as $p)
                <option value="{{ $p->idpenerimaan }}">#{{ $p->idpenerimaan }} - {{ $p->nama_vendor }} ({{ $p->tanggal }})</option>
            @endforeach
        </select>
        <button class="btn-primary" type="submit">Lanjut</button>
    </form>
    @if ($daftar->isEmpty())
        <p class="catatan">Belum ada penerimaan yang bisa diretur.</p>
    @endif
@else
    <dl class="ringkas">
        <div><dt>Penerimaan</dt><dd>#{{ $penerimaan->idpenerimaan }}</dd></div>
        <div><dt>Vendor</dt><dd>{{ $penerimaan->nama_vendor }}</dd></div>
        <div><dt>Tanggal</dt><dd>{{ $penerimaan->tanggal }}</dd></div>
    </dl>

    <form class="isian lebar" method="post" action="{{ route('retur.store') }}">
        @csrf
        <input type="hidden" name="idpenerimaan" value="{{ $penerimaan->idpenerimaan }}">

        <label>Dibuat Oleh</label>
        <select name="iduser" required>
            <option value="">-- pilih user --</option>
            @foreach ($users as $u)
                <option value="{{ $u->iduser }}" {{ (string) old('iduser') === (string) $u->iduser ? 'selected' : '' }}>
                    {{ $u->username }} ({{ $u->nama_role }})
                </option>
            @endforeach
        </select>

        <label>Barang yang diretur</label>
        <table class="tabel-isian">
            <thead>
                <tr><th>Barang</th><th>Diterima</th><th>Sudah Diretur</th><th>Jumlah Retur</th><th>Alasan</th></tr>
            </thead>
            <tbody>
                @foreach ($detail as $d)
                    <tr>
                        <td>{{ $d->nama }} ({{ $d->nama_satuan }})</td>
                        <td>{{ $d->jumlah_terima }}</td>
                        <td>{{ $d->sudah_retur }}</td>
                        <td>
                            <input type="number" name="jumlah[{{ $d->iddetail_penerimaan }}]"
                                   min="0" max="{{ $d->sisa }}" {{ $d->sisa < 1 ? 'disabled' : '' }}
                                   value="{{ old('jumlah.' . $d->iddetail_penerimaan, 0) }}">
                        </td>
                        <td>
                            <input type="text" name="alasan[{{ $d->iddetail_penerimaan }}]" maxlength="200"
                                   {{ $d->sisa < 1 ? 'disabled' : '' }}
                                   value="{{ old('alasan.' . $d->iddetail_penerimaan) }}">
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <p class="catatan">Isi jumlah retur hanya untuk barang yang dikembalikan. Alasan wajib diisi untuk barang tersebut.</p>

        <div class="tombol">
            <button class="btn-primary" type="submit">Simpan</button>
            <a class="btn-sekunder" href="{{ route('retur.create') }}">Batal</a>
        </div>
    </form>
@endif
@endsection
