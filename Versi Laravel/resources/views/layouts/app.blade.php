@php
    // Pengguna yang tampil di pojok kanan atas. Statis karena belum ada login.
    $admin = \Illuminate\Support\Facades\DB::table('v_user')
            ->where('nama_role', 'Admin')
            ->orderBy('iduser')
            ->first();
    $penggunaNama = $admin->username ?? 'Pengguna';
    $penggunaRole = $admin->nama_role ?? 'Admin';

    // 'ada' = false berarti halamannya belum dibuat (menu tampil abu-abu)
    $menu = [
        ['grup' => null, 'items' => [
            ['rute' => 'dashboard', 'label' => 'Dashboard', 'ikon' => 'dashboard', 'ada' => true],
        ]],
        ['grup' => 'Master Data', 'items' => [
            ['rute' => 'role', 'label' => 'Role', 'ikon' => 'admin_panel_settings', 'ada' => true],
            ['rute' => 'user', 'label' => 'User', 'ikon' => 'manage_accounts', 'ada' => true],
            ['rute' => 'satuan', 'label' => 'Satuan', 'ikon' => 'straighten', 'ada' => true],
            ['rute' => 'vendor', 'label' => 'Vendor', 'ikon' => 'store', 'ada' => true],
            ['rute' => 'barang', 'label' => 'Barang', 'ikon' => 'inventory_2', 'ada' => true],
            ['rute' => 'margin_penjualan', 'label' => 'Margin Penjualan', 'ikon' => 'price_change', 'ada' => true],
        ]],
        ['grup' => 'Transaksi', 'items' => [
            ['rute' => 'pengadaan', 'label' => 'Pengadaan', 'ikon' => 'shopping_cart', 'ada' => false],
            ['rute' => 'penerimaan', 'label' => 'Penerimaan', 'ikon' => 'move_to_inbox', 'ada' => false],
            ['rute' => 'penjualan', 'label' => 'Penjualan', 'ikon' => 'point_of_sale', 'ada' => false],
            ['rute' => 'retur', 'label' => 'Retur', 'ikon' => 'assignment_return', 'ada' => false],
        ]],
        ['grup' => 'Laporan', 'items' => [
            ['rute' => 'kartu_stok', 'label' => 'Kartu Stok', 'ikon' => 'receipt_long', 'ada' => false],
        ]],
    ];
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('judul') - Prissa Store</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

<aside class="sidebar">
    <div class="merek">Prissa Store</div>
    <nav>
        @foreach ($menu as $g)
            @if ($g['grup'])
                <div class="grup">{{ $g['grup'] }}</div>
            @endif
            @foreach ($g['items'] as $i)
                @if ($i['ada'])
                    <a class="nav-item {{ request()->routeIs($i['rute'], $i['rute'] . '.*') ? 'aktif' : '' }}"
                       href="{{ $i['rute'] === 'dashboard' ? route('dashboard') : route($i['rute'] . '.index') }}">
                        <span class="material-symbols-outlined">{{ $i['ikon'] }}</span>{{ $i['label'] }}
                    </a>
                @else
                    <span class="nav-item mati" title="Halaman ini belum tersedia">
                        <span class="material-symbols-outlined">{{ $i['ikon'] }}</span>{{ $i['label'] }}
                    </span>
                @endif
            @endforeach
        @endforeach
    </nav>
</aside>

<div class="konten">
    <header class="topbar">
        <span class="topbar-judul">Prissa Store Administrasi</span>
        <div class="pengguna">
            <span>{{ $penggunaNama }} - {{ $penggunaRole }}</span>
            <div class="avatar"><span class="material-symbols-outlined">person</span></div>
        </div>
    </header>

    <main>
        <div class="kepala-halaman">
            <div>
                <h1>@yield('judul')</h1>
                @hasSection('subjudul')
                    <p class="sub">@yield('subjudul')</p>
                @endif
            </div>
            @yield('tombol')
        </div>

        @if (session('sukses'))
            <div class="alert ok">{{ session('sukses') }}</div>
        @endif
        @if (session('galat'))
            <div class="alert err">{{ session('galat') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert err">
                <ul>
                    @foreach ($errors->all() as $pesan)
                        <li>{{ $pesan }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('isi')
    </main>
</div>

<!-- Konfirmasi hapus -->
<div class="modal" id="modal-hapus">
    <div class="modal-kotak">
        <h2>Hapus data ini?</h2>
        <p>Tindakan ini tidak dapat dibatalkan.</p>
        <div class="modal-aksi">
            <button type="button" class="btn-sekunder" id="hapus-batal">Batal</button>
            <button type="button" class="btn-bahaya" id="hapus-ok">Hapus</button>
        </div>
    </div>
</div>

<script>
(function () {
    var modal = document.getElementById('modal-hapus');
    var target = null;

    function tutup() { modal.classList.remove('buka'); target = null; }

    document.querySelectorAll('form.form-hapus').forEach(function (f) {
        f.addEventListener('submit', function (ev) {
            ev.preventDefault();
            target = f;
            modal.classList.add('buka');
        });
    });

    document.getElementById('hapus-batal').addEventListener('click', tutup);
    document.getElementById('hapus-ok').addEventListener('click', function () {
        if (target) { target.submit(); }
    });
    modal.addEventListener('click', function (ev) { if (ev.target === modal) { tutup(); } });
    document.addEventListener('keydown', function (ev) { if (ev.key === 'Escape') { tutup(); } });
})();
</script>
</body>
</html>
