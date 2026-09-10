-- ============================================================
-- Menu "Import Transaksi Shopee" (Menu Penjualan) + tabel staging zztemptransshopee
-- Upload file "FILE TEMPLATE IMPORT SHOPEE.xlsx" (sheet "Sheet1") -> simpan mentah
-- ke zztemptransshopee. Konversi ke fstoku menyusul (belum termasuk di sini).
-- Controller : Page::import_trans_shopee          -> modul/transaksi/penjualan/import-trans-shopee.php
--              Import_Trans_Shopee::upload         (parse + simpan)
--              Import_Trans_Shopee::view_riwayat   (list datatable)
-- Halaman    : page/import_trans_shopee
-- Jalankan sekali pada database aplikasi.
-- ============================================================

-- 1. Tabel staging (kolom sesuai header Excel template Shopee)
CREATE TABLE IF NOT EXISTS zztemptransshopee (
  id                              INT(11) NOT NULL AUTO_INCREMENT,
  no_pesanan                      VARCHAR(50)  NULL,
  status_pesanan                  VARCHAR(50)  NULL,
  alasan_pembatalan               VARCHAR(255) NULL,
  status_pembatalan_pengembalian  VARCHAR(100) NULL,
  no_resi                         VARCHAR(50)  NULL,
  opsi_pengiriman                 VARCHAR(100) NULL,
  antar_ke_counter                VARCHAR(50)  NULL,
  pesanan_dikirim_sebelum         VARCHAR(30)  NULL,
  waktu_pengiriman_diatur         VARCHAR(30)  NULL,
  waktu_pesanan_dibuat            VARCHAR(30)  NULL,
  waktu_pembayaran                VARCHAR(30)  NULL,
  tipe_pesanan                    VARCHAR(50)  NULL,
  metode_pembayaran               VARCHAR(100) NULL,
  sku_induk                       VARCHAR(100) NULL,
  nama_produk                     VARCHAR(255) NULL,
  nomor_referensi_sku             VARCHAR(100) NULL,
  nama_variasi                    VARCHAR(255) NULL,
  harga_awal                      VARCHAR(30)  NULL,
  harga_setelah_diskon            VARCHAR(30)  NULL,
  jumlah                          VARCHAR(20)  NULL,
  returned_quantity               VARCHAR(20)  NULL,
  subtotal_pesanan                VARCHAR(30)  NULL,
  total_diskon                    VARCHAR(30)  NULL,
  diskon_dari_penjual             VARCHAR(30)  NULL,
  diskon_dari_shopee              VARCHAR(30)  NULL,
  berat_produk                    VARCHAR(20)  NULL,
  jumlah_produk_dipesan           VARCHAR(20)  NULL,
  total_berat                     VARCHAR(20)  NULL,
  voucher_ditanggung_penjual      VARCHAR(30)  NULL,
  cashback_koin                   VARCHAR(30)  NULL,
  voucher_ditanggung_shopee       VARCHAR(30)  NULL,
  paket_diskon                    VARCHAR(30)  NULL,
  paket_diskon_dari_shopee        VARCHAR(30)  NULL,
  paket_diskon_dari_penjual       VARCHAR(30)  NULL,
  potongan_koin_shopee            VARCHAR(30)  NULL,
  diskon_kartu_kredit             VARCHAR(30)  NULL,
  ongkir_dibayar_pembeli          VARCHAR(30)  NULL,
  estimasi_potongan_biaya_pengiriman VARCHAR(30) NULL,
  ongkir_pengembalian_barang      VARCHAR(30)  NULL,
  total_pembayaran                VARCHAR(30)  NULL,
  perkiraan_ongkir                VARCHAR(30)  NULL,
  catatan_dari_pembeli            VARCHAR(255) NULL,
  catatan                         VARCHAR(255) NULL,
  username_pembeli                VARCHAR(100) NULL,
  nama_penerima                   VARCHAR(150) NULL,
  no_telepon                      VARCHAR(30)  NULL,
  alamat_pengiriman               VARCHAR(255) NULL,
  kota_kabupaten                  VARCHAR(100) NULL,
  provinsi                        VARCHAR(100) NULL,
  waktu_pesanan_selesai           VARCHAR(30)  NULL,
  sumber_file                     VARCHAR(255) NULL,
  diimport_oleh                   INT(11)      NULL,
  diimport_pada                   TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_no_pesanan (no_pesanan)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 2. Menu di bawah "Menu Penjualan" (MID 3), setelah "Import Shopee Income"
SET @parent = 3;
SET @urutan = (SELECT COALESCE(MAX(MURUTAN),0) + 1 FROM aamenu WHERE MPARENT = @parent);

INSERT INTO aamenu
  (MNAMA, MDESCRIPTION, MURUTAN, MPARENT, MTYPE, MACTIVE, MSHORTNAME, MLINK, MCAPTION1, MICON)
VALUES
  ('Import Transaksi Shopee', 'Import file template transaksi Shopee ke tabel staging zztemptransshopee',
   @urutan, @parent, 2, 1, 'import_trans_shopee', 'page/import_trans_shopee', 'Import Transaksi Shopee', 'fas fa-file-import');

SET @mid_new = LAST_INSERT_ID();

-- 3. Hak akses: salin dari menu "Import Shopee Income"
SET @mid_src = (SELECT MID FROM aamenu WHERE MLINK = 'page/import_shopee_income' LIMIT 1);

INSERT INTO aausermenu (AUIDUSER, AUIDMENU, AUADD, AUEDIT, AUDELL, AUPRINT, AUAPPROVE)
SELECT AUIDUSER, @mid_new, AUADD, AUEDIT, AUDELL, AUPRINT, AUAPPROVE
  FROM (SELECT * FROM aausermenu WHERE AUIDMENU = @mid_src) src;
