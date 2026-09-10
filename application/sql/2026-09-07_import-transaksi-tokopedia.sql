-- ============================================================
-- Menu "Import Transaksi Tokopedia" (Menu Penjualan) + tabel staging zztemptranstokped
-- Upload file "Semua pesanan-YYYY-MM-DD-HH_MM.xlsx" (sheet "OrderSKUList") -> simpan
-- mentah ke zztemptranstokped. Konversi ke fstoku menyusul (belum termasuk di sini).
-- Controller : Page::import_trans_tokped          -> modul/transaksi/penjualan/import-trans-tokped.php
--              Import_Trans_Tokped::upload         (parse + simpan)
--              Import_Trans_Tokped::view_riwayat   (list datatable)
-- Halaman    : page/import_trans_tokped
-- Jalankan sekali pada database aplikasi.
-- ============================================================

-- 1. Tabel staging (kolom sesuai header Excel export Tokopedia / OrderSKUList)
CREATE TABLE IF NOT EXISTS zztemptranstokped (
  id                              INT(11) NOT NULL AUTO_INCREMENT,
  order_id                        VARCHAR(50)  NULL,
  order_status                    VARCHAR(50)  NULL,
  order_substatus                 VARCHAR(50)  NULL,
  cancelation_return_type         VARCHAR(100) NULL,
  normal_or_preorder              VARCHAR(30)  NULL,
  sku_id                          VARCHAR(50)  NULL,
  seller_sku                      VARCHAR(100) NULL,
  product_name                    VARCHAR(500) NULL,
  variation                       VARCHAR(255) NULL,
  quantity                        VARCHAR(20)  NULL,
  sku_quantity_of_return          VARCHAR(20)  NULL,
  sku_unit_original_price         VARCHAR(30)  NULL,
  sku_subtotal_before_discount    VARCHAR(30)  NULL,
  sku_platform_discount           VARCHAR(30)  NULL,
  sku_seller_discount             VARCHAR(30)  NULL,
  sku_subtotal_after_discount     VARCHAR(30)  NULL,
  shipping_fee_after_discount     VARCHAR(30)  NULL,
  original_shipping_fee           VARCHAR(30)  NULL,
  shipping_fee_seller_discount    VARCHAR(30)  NULL,
  shipping_fee_platform_discount  VARCHAR(30)  NULL,
  distance_shipping_fee           VARCHAR(30)  NULL,
  distance_fee                    VARCHAR(30)  NULL,
  order_refund_amount             VARCHAR(30)  NULL,
  payment_platform_discount       VARCHAR(30)  NULL,
  buyer_service_fee               VARCHAR(30)  NULL,
  handling_fee                    VARCHAR(30)  NULL,
  shipping_insurance              VARCHAR(30)  NULL,
  item_insurance                  VARCHAR(30)  NULL,
  order_amount                    VARCHAR(30)  NULL,
  created_time                    VARCHAR(30)  NULL,
  paid_time                       VARCHAR(30)  NULL,
  rts_time                        VARCHAR(30)  NULL,
  shipped_time                    VARCHAR(30)  NULL,
  delivered_time                  VARCHAR(30)  NULL,
  cancelled_time                  VARCHAR(30)  NULL,
  cancel_by                       VARCHAR(50)  NULL,
  cancel_reason                   VARCHAR(255) NULL,
  fulfillment_type                VARCHAR(50)  NULL,
  warehouse_name                  VARCHAR(100) NULL,
  tracking_id                     VARCHAR(50)  NULL,
  delivery_option                 VARCHAR(100) NULL,
  shipping_provider_name          VARCHAR(100) NULL,
  buyer_message                   VARCHAR(500) NULL,
  buyer_username                  VARCHAR(100) NULL,
  recipient                       VARCHAR(150) NULL,
  phone                           VARCHAR(30)  NULL,
  zipcode                         VARCHAR(20)  NULL,
  country                         VARCHAR(50)  NULL,
  province                        VARCHAR(100) NULL,
  regency_and_city                VARCHAR(100) NULL,
  districts                       VARCHAR(100) NULL,
  villages                        VARCHAR(100) NULL,
  detail_address                  VARCHAR(500) NULL,
  additional_address_information  VARCHAR(500) NULL,
  payment_method                  VARCHAR(100) NULL,
  weight_kg                       VARCHAR(20)  NULL,
  product_category                VARCHAR(255) NULL,
  package_id                      VARCHAR(50)  NULL,
  purchase_channel                VARCHAR(50)  NULL,
  seller_note                     VARCHAR(500) NULL,
  checked_status                  VARCHAR(30)  NULL,
  checked_marked_by               VARCHAR(100) NULL,
  tokopedia_invoice_number        VARCHAR(50)  NULL,
  order_channel                   VARCHAR(50)  NULL,
  creator_handle                  VARCHAR(100) NULL,
  sumber_file                     VARCHAR(255) NULL,
  diimport_oleh                   INT(11)      NULL,
  diimport_pada                   TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_order_id (order_id),
  KEY idx_sku_id (sku_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 2. Menu di bawah "Menu Penjualan" (MID 3), setelah "Import Transaksi Shopee"
SET @parent = 3;
SET @urutan = (SELECT COALESCE(MAX(MURUTAN),0) + 1 FROM aamenu WHERE MPARENT = @parent);

INSERT INTO aamenu
  (MNAMA, MDESCRIPTION, MURUTAN, MPARENT, MTYPE, MACTIVE, MSHORTNAME, MLINK, MCAPTION1, MICON)
VALUES
  ('Import Transaksi Tokopedia', 'Import file export pesanan Tokopedia ke tabel staging zztemptranstokped',
   @urutan, @parent, 2, 1, 'import_trans_tokped', 'page/import_trans_tokped', 'Import Transaksi Tokopedia', 'fas fa-file-import');

SET @mid_new = LAST_INSERT_ID();

-- 3. Hak akses: salin dari menu "Import Transaksi Shopee"
SET @mid_src = (SELECT MID FROM aamenu WHERE MLINK = 'page/import_trans_shopee' LIMIT 1);

INSERT INTO aausermenu (AUIDUSER, AUIDMENU, AUADD, AUEDIT, AUDELL, AUPRINT, AUAPPROVE)
SELECT AUIDUSER, @mid_new, AUADD, AUEDIT, AUDELL, AUPRINT, AUAPPROVE
  FROM (SELECT * FROM aausermenu WHERE AUIDMENU = @mid_src) src;
