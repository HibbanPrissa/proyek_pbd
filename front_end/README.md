# Prissa Store

Prissa Store adalah aplikasi web yang dibuat untuk proyek Praktikum Pemrograman Basis Data. Pada tahap ini, aplikasi mengelola data master, yaitu role, user, satuan, vendor, barang, dan margin penjualan. Pengelolaan data transaksi akan ditambahkan pada tahap berikutnya.

Aplikasi dibuat menggunakan PHP dan basis data MySQL (MariaDB).

## Isi Folder

- `koneksi.php`: pengaturan koneksi basis data dan fungsi pendukung yang dipakai di seluruh halaman.
- `header.php` dan `footer.php`: sidebar, bilah atas, dan konfirmasi hapus yang dipakai di seluruh halaman.
- `style.css`: pengaturan tampilan.
- `index.php`: halaman utama yang menampilkan jumlah data pada setiap tabel master.
- `role.php`, `user.php`, `satuan.php`, `vendor.php`, `barang.php`, dan `margin_penjualan.php`: halaman untuk mengelola masing-masing data master.

## Cara Kerja

Setiap halaman memiliki tampilan daftar data serta form untuk menambah, mengubah, dan menghapus data. Daftar data ditampilkan melalui view (`v_role`, `v_user`, `v_satuan`, `v_vendor`, `v_barang`, dan `v_margin_penjualan`). Proses tambah, ubah, dan hapus dilakukan langsung pada tabel yang bersangkutan.

## Catatan

- Password user disimpan dalam bentuk hash (SHA-256), dan view `v_user` tidak menampilkan kolom password.
- Data yang masih digunakan oleh tabel lain tidak dapat dihapus, misalnya satuan yang sudah dipakai oleh barang. Aplikasi akan menampilkan pesan penolakan.
- Menu Transaksi dan Laporan belum aktif karena halamannya belum dibuat.
- Aplikasi belum memiliki halaman login. Nama pengguna di bilah atas masih tetap dan diatur pada `koneksi.php`.
- Ikon dan huruf dimuat dari Google Fonts, sehingga membutuhkan koneksi internet.
