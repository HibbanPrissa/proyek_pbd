DELIMITER $$
CREATE FUNCTION fn_stok_barang(p_idbarang INT)
RETURNS INT
READS SQL DATA
BEGIN
    DECLARE v_stok INT DEFAULT 0;

    SELECT stock INTO v_stok
    FROM kartu_stok
    WHERE idbarang = p_idbarang
    ORDER BY idkartu_stok DESC
    LIMIT 1;

    RETURN v_stok;
END$$
DELIMITER ;

DELIMITER $$
CREATE TRIGGER trg_stok_penerimaan
AFTER INSERT ON detail_penerimaan
FOR EACH ROW
BEGIN
    INSERT INTO kartu_stok (jenis_transaksi, masuk, keluar, stock, idtransaksi, idbarang)
    VALUES ('P',
            NEW.jumlah_terima,
            0,
            fn_stok_barang(NEW.barang_idbarang) + NEW.jumlah_terima,
            NEW.idpenerimaan,
            NEW.barang_idbarang);
END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE sp_hitung_total_pengadaan(IN p_idpengadaan BIGINT)
BEGIN
    DECLARE v_subtotal INT DEFAULT 0;
    DECLARE v_ppn INT DEFAULT 0;

    IF NOT EXISTS (SELECT 1 FROM pengadaan WHERE idpengadaan = p_idpengadaan) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Pengadaan tidak ditemukan.';
    END IF;

    SELECT IFNULL(SUM(sub_total), 0) INTO v_subtotal
    FROM detail_pengadaan
    WHERE idpengadaan = p_idpengadaan;

    SET v_ppn = ROUND(v_subtotal * 0.11);
    
    UPDATE pengadaan
    SET subtotal_nilai = v_subtotal,
        ppn            = v_ppn,
        total_nilai    = v_subtotal + v_ppn
    WHERE idpengadaan = p_idpengadaan;

    SELECT idpengadaan, subtotal_nilai, ppn, total_nilai
    FROM pengadaan
    WHERE idpengadaan = p_idpengadaan;
END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE sp_set_margin_aktif(IN p_idmargin INT)
BEGIN
    IF NOT EXISTS (SELECT 1 FROM margin_penjualan WHERE idmargin_penjualan = p_idmargin) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Margin penjualan tidak ditemukan.';
    END IF;

    START TRANSACTION;
        UPDATE margin_penjualan SET status = 0;
        UPDATE margin_penjualan SET status = 1 WHERE idmargin_penjualan = p_idmargin;
    COMMIT;

    SELECT idmargin_penjualan, persen, status FROM margin_penjualan ORDER BY idmargin_penjualan;
END$$
DELIMITER ;

DELIMITER $$
CREATE FUNCTION fn_harga_jual(p_idbarang INT)
RETURNS INT
READS SQL DATA
BEGIN
    DECLARE v_harga INT DEFAULT 0;
    DECLARE v_persen DECIMAL(10,4) DEFAULT 0;

    SELECT harga INTO v_harga
    FROM barang
    WHERE idbarang = p_idbarang;

    SELECT persen INTO v_persen
    FROM margin_penjualan
    WHERE status = 1
    ORDER BY idmargin_penjualan DESC
    LIMIT 1;

    RETURN ROUND(v_harga * (100 + v_persen) / 100);
END$$
DELIMITER ;