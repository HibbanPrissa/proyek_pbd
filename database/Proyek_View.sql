-- VIEW UNTUK TABEL MASTER

-- 1. View Role
CREATE OR REPLACE VIEW v_role AS
SELECT
    r.idrole,
    r.nama_role
FROM role r;

-- 2. View User
CREATE OR REPLACE VIEW v_user AS
SELECT
    u.iduser,
    u.username,
    u.idrole,
    r.nama_role
FROM user u
JOIN role r ON r.idrole = u.idrole;

-- 3. View Satuan
CREATE OR REPLACE VIEW v_satuan AS
SELECT
    s.idsatuan,
    s.nama_satuan,
    s.status,
    CASE s.status
        WHEN 1 THEN 'Aktif'
        ELSE 'Nonaktif'
    END AS status_label
FROM satuan s;

-- 4. View Vendor
CREATE OR REPLACE VIEW v_vendor AS
SELECT
    v.idvendor,
    v.nama_vendor,
    v.badan_hukum,
    CASE v.badan_hukum
        WHEN 'P' THEN 'PT'
        WHEN 'C' THEN 'CV'
        WHEN 'U' THEN 'UD'
        ELSE '-'
    END AS badan_hukum_label,
    v.status,
    CASE v.status
        WHEN 'A' THEN 'Aktif'
        ELSE 'Nonaktif'
    END AS status_label
FROM vendor v;

-- 5. View Barang
CREATE OR REPLACE VIEW v_barang AS
SELECT
    b.idbarang,
    b.nama,
    b.jenis,
    CASE b.jenis
        WHEN 'M' THEN 'Makanan'
        WHEN 'N' THEN 'Non Makanan'
        ELSE '-'
    END AS jenis_label,
    b.idsatuan,
    s.nama_satuan,
    b.harga,
    b.status,
    CASE b.status
        WHEN 1 THEN 'Aktif'
        ELSE 'Nonaktif'
    END AS status_label
FROM barang b
JOIN satuan s ON s.idsatuan = b.idsatuan;

-- 6. View Margin Penjualan
CREATE OR REPLACE VIEW v_margin_penjualan AS
SELECT
    m.idmargin_penjualan,
    m.persen,
    m.status,
    CASE m.status
        WHEN 1 THEN 'Aktif'
        ELSE 'Nonaktif'
    END AS status_label,
    m.iduser,
    u.username AS dibuat_oleh,
    m.created_at,
    m.updated_at
FROM margin_penjualan m
JOIN user u ON u.iduser = m.iduser;


SHOW FULL TABLES WHERE Table_type = 'VIEW';