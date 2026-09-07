-- ============================================================
-- Kolom SKU Marketplace + Menu "Update SKU Marketplace"
-- (di bawah menu "Data Item POS"), tabel & form seperti Update Harga Marketplace.
--   - Kolom tabel : kode item, nama item, bitem2.i2skushopee, bitem2.i2skutokopedia
--   - Form modal  : kode, nama (read-only), SKU Shopee, SKU Tokopedia (edit)
--   - Fungsi      : hanya mengupdate kolom bitem2.I2SKUSHOPEE & I2SKUTOKOPEDIA
-- Controller : Page::updsku_mp                       -> modul/master/table-update-sku-mp.php
--              Datatable_Master::view_table_update_sku_mp_list  (list datatable)
--              Modal::form_update_sku_mp             -> modul/master/form-update-sku-mp.php
--              Master_Item_POS::getskump             (isi form modal)
--              Master_Item_POS::updskump             (simpan)
-- Model      : M_Master_Item_POS::updateSkuMp
-- Halaman    : page/updsku_mp
-- Jalankan sekali pada database aplikasi.
-- ============================================================

-- 1. Kolom SKU marketplace di bitem2 (varchar 50)
ALTER TABLE bitem2
  ADD COLUMN I2SKUSHOPEE    varchar(50) NULL AFTER I2HARGAJUALMP,
  ADD COLUMN I2SKUTOKOPEDIA varchar(50) NULL AFTER I2SKUSHOPEE;

-- 2. Induk = menu "Data Item POS"
SET @parent = (SELECT MID FROM aamenu WHERE MLINK = 'page/item_pos' LIMIT 1);
SET @urutan = (SELECT COALESCE(MAX(MURUTAN),0) + 1 FROM aamenu WHERE MPARENT = @parent);

-- 3. Daftarkan menu di sidebar
INSERT INTO aamenu
  (MNAMA, MDESCRIPTION, MURUTAN, MPARENT, MTYPE, MACTIVE, MSHORTNAME, MLINK, MCAPTION1, MICON)
VALUES
  ('Update SKU Marketplace', 'Update SKU Shopee & Tokopedia (bitem2) per item',
   @urutan, @parent, 3, 1, 'updsku_mp', 'page/updsku_mp', 'Update SKU Marketplace', 'fas fa-barcode');

SET @mid_new = LAST_INSERT_ID();

-- 4. Hak akses: salin dari user yang punya akses menu "Data Item POS"
INSERT INTO aausermenu (AUIDUSER, AUIDMENU, AUADD, AUEDIT, AUDELL, AUPRINT, AUAPPROVE)
SELECT AUIDUSER, @mid_new, AUADD, AUEDIT, AUDELL, AUPRINT, AUAPPROVE
  FROM (SELECT * FROM aausermenu WHERE AUIDMENU = @parent) src;
