@extends('layouts.app')

@section('judul', 'Dashboard')
@section('subjudul', 'Ringkasan jumlah data master')

@section('isi')
<div class="kartu-wrap">
    @foreach ($daftar as $d)
        <a class="kartu" href="{{ route($d['rute'] . '.index') }}">
            <div class="kartu-atas">
                <span>Master Entitas</span>
                <span class="material-symbols-outlined">{{ $d['ikon'] }}</span>
            </div>
            <div>
                <div class="angka">{{ $d['jumlah'] }}</div>
                <div class="nama">{{ $d['nama'] }}</div>
            </div>
        </a>
    @endforeach
</div>
@endsection
