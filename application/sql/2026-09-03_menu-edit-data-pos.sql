-- ============================================================
-- Menu "Edit Data POS" (Menu Penjualan)
-- Edit cepat diskon baris transaksi POS yang pembayarannya lewat merchant.
--   - Update fstokd.sddiskonpersen & fstokd.sddiskon per baris
--   - Hitung ulang fstoku.sumerchantjumlah = SUM(sdkeluar*sdharga - sddiskon) per transaksi
-- Controller : PJ_Edit_Data_POS  (getdata, savedata)
-- Model      : M_PJ_Edit_Data_POS
-- Halaman    : page/edit_data_pos  ->  modul/transaksi/penjualan/edit-data-pos.php
-- Jalankan sekali pada database aplikasi.
-- ============================================================

-- 1. Ambil acuan dari menu "Penjualan Tunai / POS" (page/pos) yang sudah ada di Menu Penjualan
SET @mid_pos   = (SELECT MID FROM aamenu WHERE MLINK = 'page/pos' LIMIT 1);
SET @parent    = (SELECT MPARENT FROM aamenu WHERE MID = @mid_pos);
SET @mtype_pos = (SELECT MTYPE FROM aamenu WHERE MID = @mid_pos);
SET @urutan    = (SELECT COALESCE(MAX(MURUTAN),0) + 1 FROM aamenu WHERE MPARENT = @parent);

-- 2. Daftarkan menu di sidebar (di bawah induk "Menu Penjualan")
INSERT INTO aamenu
  (MNAMA, MDESCRIPTION, MURUTAN, MPARENT, MTYPE, MACTIVE, MSHORTNAME, MLINK, MCAPTION1, MICON)
VALUES
  ('Edit Data POS', 'Edit cepat diskon & merchant baris transaksi POS',
   @urutan, @parent, @mtype_pos, 1, 'edit_data_pos', 'page/edit_data_pos', 'Edit Data POS', 'fas fa-edit');

SET @mid_new = LAST_INSERT_ID();

-- 3. Hak akses: salin dari user yang saat ini punya akses menu "Penjualan Tunai / POS"
INSERT INTO aausermenu (AUIDUSER, AUIDMENU, AUADD, AUEDIT, AUDELL, AUPRINT, AUAPPROVE)
SELECT AUIDUSER, @mid_new, AUADD, AUEDIT, AUDELL, AUPRINT, AUAPPROVE
  FROM (SELECT * FROM aausermenu WHERE AUIDMENU = @mid_pos) src;
