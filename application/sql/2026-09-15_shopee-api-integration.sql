-- ============================================================
-- Integrasi Shopee Open Platform API v2:
--   1. Seting API Marketplace Shopee  (Setup Program)
--   2. Cek Produk Shopee (API)        (Penjualan)
--   3. Tarik Penjualan Shopee (API)   (Penjualan)
--
-- Tabel:
--   aashopeeapi        - kredensial & token (partner_id/key, shop_id,
--                         access_token/refresh_token + masa berlaku)
--   zzshopeeproduk      - cache hasil "Cek Produk" (product/get_item_*)
--   zzshopeeorder        - header order hasil tarik dari API (order/get_order_*)
--   zzshopeeorderdetail  - baris item per order
--
-- Controller : Shopee_Api.php   Library: application/libraries/Shopee_Api.php
-- Halaman    : page/shopee_api_setting, page/shopee_api_produk, page/shopee_api_penjualan
-- Jalankan sekali pada database aplikasi.
-- ============================================================

-- 1. Kredensial & token (satu baris aktif per toko; cabang default = 18/Marketplace)
CREATE TABLE IF NOT EXISTS aashopeeapi (
  id                    INT(11) NOT NULL AUTO_INCREMENT,
  nama_toko             VARCHAR(100) NULL,
  partner_id            VARCHAR(50)  NULL,
  partner_key           VARCHAR(255) NULL,
  shop_id               VARCHAR(50)  NULL,
  redirect_uri          VARCHAR(255) NULL,
  environment           VARCHAR(10)  NOT NULL DEFAULT 'live',   -- 'live' | 'sandbox'
  cabang                INT(11)      NOT NULL DEFAULT 18,
  access_token          VARCHAR(255) NULL,
  refresh_token         VARCHAR(255) NULL,
  access_token_expire   DATETIME     NULL,
  refresh_token_expire  DATETIME     NULL,
  aktif                 TINYINT(1)   NOT NULL DEFAULT 1,
  diubah_oleh           INT(11)      NULL,
  diubah_pada           TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 2. Cache "Cek Produk" (product/get_item_list + get_item_base_info)
CREATE TABLE IF NOT EXISTS zzshopeeproduk (
  id                INT(11) NOT NULL AUTO_INCREMENT,
  item_id           BIGINT(20)    NULL,
  item_sku          VARCHAR(100)  NULL,
  item_name         VARCHAR(500)  NULL,
  category_id       BIGINT(20)    NULL,
  harga             DOUBLE        NULL,
  stok              INT(11)       NULL,
  item_status       VARCHAR(30)   NULL,
  cocok_kode_lokal  VARCHAR(25)   NULL,   -- diisi kalau item_sku cocok dgn bitem2.I2SKUSHOPEE / bitem.IKODE
  update_time       DATETIME      NULL,
  raw_json          LONGTEXT      NULL,
  ditarik_oleh      INT(11)       NULL,
  ditarik_pada      TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY idx_item_id (item_id),
  KEY idx_item_sku (item_sku)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 3. Header order hasil "Tarik Penjualan" (order/get_order_list + get_order_detail)
CREATE TABLE IF NOT EXISTS zzshopeeorder (
  id                INT(11) NOT NULL AUTO_INCREMENT,
  order_sn          VARCHAR(50)   NOT NULL,
  order_status      VARCHAR(30)   NULL,
  create_time       DATETIME      NULL,
  update_time       DATETIME      NULL,
  pay_time          DATETIME      NULL,
  total_amount      DOUBLE        NULL,
  currency          VARCHAR(10)   NULL,
  buyer_username    VARCHAR(100)  NULL,
  recipient_name    VARCHAR(150)  NULL,
  recipient_phone   VARCHAR(30)   NULL,
  payment_method    VARCHAR(100)  NULL,
  shipping_carrier  VARCHAR(100)  NULL,
  tracking_number   VARCHAR(100)  NULL,
  note              VARCHAR(500)  NULL,
  raw_json          LONGTEXT      NULL,
  sudah_diproses    TINYINT(1)    NOT NULL DEFAULT 0,   -- ditandai '1' saat sudah dikonversi ke fstoku (menyusul)
  ditarik_oleh      INT(11)       NULL,
  ditarik_pada      TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY idx_order_sn (order_sn),
  KEY idx_create_time (create_time),
  KEY idx_order_status (order_status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 4. Baris item per order
CREATE TABLE IF NOT EXISTS zzshopeeorderdetail (
  id                INT(11) NOT NULL AUTO_INCREMENT,
  order_sn          VARCHAR(50)   NOT NULL,
  item_id           BIGINT(20)    NULL,
  item_sku          VARCHAR(100)  NULL,
  item_name         VARCHAR(500)  NULL,
  model_id          BIGINT(20)    NULL,
  model_sku         VARCHAR(100)  NULL,
  model_name        VARCHAR(255)  NULL,
  qty               INT(11)       NULL,
  harga_asli        DOUBLE        NULL,
  harga_diskon      DOUBLE        NULL,
  PRIMARY KEY (id),
  KEY idx_order_sn (order_sn)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 5. Menu "Seting API Marketplace Shopee" di bawah "Setup Program" (MID 199)
SET @urutan_setup = (SELECT COALESCE(MAX(MURUTAN),0) + 1 FROM aamenu WHERE MPARENT = 199);

INSERT INTO aamenu
  (MNAMA, MDESCRIPTION, MURUTAN, MPARENT, MTYPE, MACTIVE, MSHORTNAME, MLINK, MCAPTION1, MICON)
VALUES
  ('Seting API Marketplace Shopee', 'Kredensial partner_id/partner_key, otorisasi & token Shopee Open Platform',
   @urutan_setup, 199, 4, 1, 'shopee_api_setting', 'page/shopee_api_setting', 'Seting API Marketplace Shopee', 'fas fa-plug');

SET @mid_setting = LAST_INSERT_ID();

INSERT INTO aausermenu (AUIDUSER, AUIDMENU, AUADD, AUEDIT, AUDELL, AUPRINT, AUAPPROVE)
SELECT AUIDUSER, @mid_setting, AUADD, AUEDIT, AUDELL, AUPRINT, AUAPPROVE
  FROM (SELECT * FROM aausermenu WHERE AUIDMENU = 199) src;

-- 6. Menu "Cek Produk Shopee (API)" & "Tarik Penjualan Shopee (API)" di bawah "Penjualan" (MID 3)
SET @urutan_pj1 = (SELECT COALESCE(MAX(MURUTAN),0) + 1 FROM aamenu WHERE MPARENT = 3);

INSERT INTO aamenu
  (MNAMA, MDESCRIPTION, MURUTAN, MPARENT, MTYPE, MACTIVE, MSHORTNAME, MLINK, MCAPTION1, MICON)
VALUES
  ('Cek Produk Shopee (API)', 'Tarik & cocokkan daftar produk toko Shopee via API',
   @urutan_pj1, 3, 2, 1, 'shopee_api_produk', 'page/shopee_api_produk', 'Cek Produk Shopee (API)', 'fab fa-shopify');

SET @mid_produk = LAST_INSERT_ID();

INSERT INTO aausermenu (AUIDUSER, AUIDMENU, AUADD, AUEDIT, AUDELL, AUPRINT, AUAPPROVE)
SELECT AUIDUSER, @mid_produk, AUADD, AUEDIT, AUDELL, AUPRINT, AUAPPROVE
  FROM (SELECT * FROM aausermenu WHERE AUIDMENU = (SELECT MID FROM aamenu WHERE MLINK='page/import_trans_shopee' LIMIT 1)) src;

SET @urutan_pj2 = @urutan_pj1 + 1;

INSERT INTO aamenu
  (MNAMA, MDESCRIPTION, MURUTAN, MPARENT, MTYPE, MACTIVE, MSHORTNAME, MLINK, MCAPTION1, MICON)
VALUES
  ('Tarik Penjualan Shopee (API)', 'Tarik order dari toko Shopee via API (order/get_order_list + get_order_detail)',
   @urutan_pj2, 3, 2, 1, 'shopee_api_penjualan', 'page/shopee_api_penjualan', 'Tarik Penjualan Shopee (API)', 'fab fa-shopify');

SET @mid_order = LAST_INSERT_ID();

INSERT INTO aausermenu (AUIDUSER, AUIDMENU, AUADD, AUEDIT, AUDELL, AUPRINT, AUAPPROVE)
SELECT AUIDUSER, @mid_order, AUADD, AUEDIT, AUDELL, AUPRINT, AUAPPROVE
  FROM (SELECT * FROM aausermenu WHERE AUIDMENU = (SELECT MID FROM aamenu WHERE MLINK='page/import_trans_shopee' LIMIT 1)) src;
