-- ============================================================
-- Menu "Update Harga Marketplace" (di bawah menu "Data Item POS")
-- Tampilan tabel seperti "Data Item POS", diedit lewat form modal:
--   - Kolom tabel : kode, nama, bitem.ihargajual1, bitem2.i2hargajualmp
--   - Form modal  : kode, nama, harga jual 1 (read-only), harga jual MP (edit)
--   - Fungsi      : hanya mengupdate kolom bitem2.I2HARGAJUALMP
-- Controller : Page::updharga_mp                      -> modul/master/table-update-harga-mp.php
--              Datatable_Master::view_table_update_harga_mp_list  (list datatable)
--              Modal::form_update_harga_mp            -> modul/master/form-update-harga-mp.php
--              Master_Item_POS::gethargamp            (isi form modal)
--              Master_Item_POS::updhargamp            (simpan)
-- Model      : M_Master_Item_POS::updateHargaMp
-- Halaman    : page/updharga_mp
-- Jalankan sekali pada database aplikasi.
-- ============================================================

-- 1. Induk = menu "Data Item POS"
SET @parent = (SELECT MID FROM aamenu WHERE MLINK = 'page/item_pos' LIMIT 1);
SET @urutan = (SELECT COALESCE(MAX(MURUTAN),0) + 1 FROM aamenu WHERE MPARENT = @parent);

-- 2. Daftarkan menu di sidebar
INSERT INTO aamenu
  (MNAMA, MDESCRIPTION, MURUTAN, MPARENT, MTYPE, MACTIVE, MSHORTNAME, MLINK, MCAPTION1, MICON)
VALUES
  ('Update Harga Marketplace', 'Update cepat harga marketplace (bitem2.i2hargajualmp) per item',
   @urutan, @parent, 3, 1, 'updharga_mp', 'page/updharga_mp', 'Update Harga Marketplace', 'fas fa-tag');

SET @mid_new = LAST_INSERT_ID();

-- 3. Hak akses: salin dari user yang punya akses menu "Data Item POS"
INSERT INTO aausermenu (AUIDUSER, AUIDMENU, AUADD, AUEDIT, AUDELL, AUPRINT, AUAPPROVE)
SELECT AUIDUSER, @mid_new, AUADD, AUEDIT, AUDELL, AUPRINT, AUAPPROVE
  FROM (SELECT * FROM aausermenu WHERE AUIDMENU = @parent) src;
