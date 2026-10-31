USE proyek_pbd

CREATE OR REPLACE VIEW v_pengadaan AS
SELECT
    p.idpengadaan,
    p.`timestamp` AS tanggal,
    p.vendor_idvendor AS idvendor,
    v.nama_vendor,
    p.user_iduser AS iduser,
    u.username,
    p.status,
    CASE p.status
        WHEN 'P' THEN 'Proses'
        WHEN 'S' THEN 'Selesai'
        ELSE 'Batal'
    END AS status_label,
    p.subtotal_nilai,
    p.ppn,
    p.total_nilai
FROM pengadaan p
JOIN vendor v ON v.idvendor = p.vendor_idvendor
JOIN user u ON u.iduser = p.user_iduser;

CREATE OR REPLACE VIEW v_detail_pengadaan AS
SELECT
    d.iddetail_pengadaan,
    d.idpengadaan,
    d.idbarang,
    b.nama,
    s.nama_satuan,
    d.harga_satuan,
    d.jumlah,
    d.sub_total
FROM detail_pengadaan d
JOIN barang b ON b.idbarang = d.idbarang
JOIN satuan s ON s.idsatuan = b.idsatuan;

CREATE OR REPLACE VIEW v_penerimaan AS
SELECT
    n.idpenerimaan,
    n.created_at AS tanggal,
    n.idpengadaan,
    v.nama_vendor,
    n.iduser,
    u.username,
    n.status,
    CASE n.status
        WHEN 'S' THEN 'Selesai'
        ELSE '-'
    END AS status_label
FROM penerimaan n
JOIN pengadaan p ON p.idpengadaan = n.idpengadaan
JOIN vendor v ON v.idvendor = p.vendor_idvendor
JOIN user u ON u.iduser = n.iduser;

CREATE OR REPLACE VIEW v_detail_penerimaan AS
SELECT
    d.iddetail_penerimaan,
    d.idpenerimaan,
    d.barang_idbarang AS idbarang,
    b.nama,
    s.nama_satuan,
    d.jumlah_terima,
    d.harga_satuan_terima,
    d.sub_total_terima
FROM detail_penerimaan d
JOIN barang b ON b.idbarang = d.barang_idbarang
JOIN satuan s ON s.idsatuan = b.idsatuan;

CREATE OR REPLACE VIEW v_kartu_stok AS
SELECT
    k.idkartu_stok,
    k.created_at,
    k.idbarang,
    b.nama,
    k.jenis_transaksi,
    CASE k.jenis_transaksi
        WHEN 'P' THEN 'Penerimaan'
        WHEN 'J' THEN 'Penjualan'
        WHEN 'R' THEN 'Retur'
        ELSE k.jenis_transaksi
    END AS jenis_label,
    k.masuk,
    k.keluar,
    k.stock,
    k.idtransaksi
FROM kartu_stok k
JOIN barang b ON b.idbarang = k.idbarang;

CREATE OR REPLACE VIEW v_penjualan AS
SELECT
    p.idpenjualan,
    p.created_at AS tanggal,
    p.iduser,
    u.username,
    p.idmargin_penjualan,
    m.persen AS margin_persen,
    p.subtotal_nilai,
    p.ppn,
    p.total_nilai
FROM penjualan p
JOIN user u ON u.iduser = p.iduser
JOIN margin_penjualan m ON m.idmargin_penjualan = p.idmargin_penjualan;

CREATE OR REPLACE VIEW v_detail_penjualan AS
SELECT
    d.iddetail_penjualan,
    d.penjualan_idpenjualan AS idpenjualan,
    d.idbarang,
    b.nama,
    s.nama_satuan,
    d.harga_satuan,
    d.jumlah,
    d.subtotal
FROM detail_penjualan d
JOIN barang b ON b.idbarang = d.idbarang
JOIN satuan s ON s.idsatuan = b.idsatuan;

CREATE OR REPLACE VIEW v_retur AS
SELECT
    r.idretur,
    r.created_at AS tanggal,
    r.idpenerimaan,
    v.nama_vendor,
    r.iduser,
    u.username
FROM retur r
JOIN penerimaan n ON n.idpenerimaan = r.idpenerimaan
JOIN pengadaan p ON p.idpengadaan = n.idpengadaan
JOIN vendor v ON v.idvendor = p.vendor_idvendor
JOIN user u ON u.iduser = r.iduser;

CREATE OR REPLACE VIEW v_detail_retur AS
SELECT
    d.iddetail_retur,
    d.idretur,
    d.iddetail_penerimaan,
    dp.barang_idbarang AS idbarang,
    b.nama,
    s.nama_satuan,
    d.jumlah,
    d.alasan
FROM detail_retur d
JOIN detail_penerimaan dp ON dp.iddetail_penerimaan = d.iddetail_penerimaan
JOIN barang b ON b.idbarang = dp.barang_idbarang
JOIN satuan s ON s.idsatuan = b.idsatuan;