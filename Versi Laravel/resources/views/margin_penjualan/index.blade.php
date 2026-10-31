@extends('layouts.app')

@section('judul', 'Data Margin Penjualan')
@section('subjudul', 'Master data persentase margin penjualan')

@section('tombol')
    <a class="btn-primary" href="{{ route('margin_penjualan.create') }}">
        <span class="material-symbols-outlined">add</span>Tambah Margin
    </a>
@endsection

@section('isi')
<div class="tabel-wrap">
    <table>
        <tr><th class="c">No</th><th>Persen</th><th class="c">Status</th><th>Dibuat Oleh</th><th>Diperbarui</th><th class="kanan">Aksi</th></tr>
        @forelse ($rows as $i => $r)
            <tr>
                <td class="c">{{ $i + 1 }}</td>
                <td>{{ rtrim(rtrim(number_format($r->persen, 2, ',', '.'), '0'), ',') }}%</td>
                <td class="c">@include('partials.badge', ['label' => $r->status_label])</td>
                <td>{{ $r->dibuat_oleh }}</td>
                <td>{{ $r->updated_at }}</td>
                <td class="aksi kanan">
                    <a class="btn-ubah" href="{{ route('margin_penjualan.edit', $r->idmargin_penjualan) }}">Ubah</a>
                    <form method="post" action="{{ route('margin_penjualan.destroy', $r->idmargin_penjualan) }}" class="form-hapus">
                        @csrf
                        @method('DELETE')
                        <button class="btn-hapus" type="submit">Hapus</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="6" class="kosong">Belum ada data.</td></tr>
        @endforelse
    </table>
</div>
@endsection
