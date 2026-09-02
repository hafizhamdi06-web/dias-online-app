-- ============================================================
-- Laporan "Laporan Penjualan Per Barang" (sistem aareport)
-- Muncul di menu Laporan > Laporan Penjualan
-- Filter: tanggal, cabang, Jenis Merchant (fstoku.SUMERCHANTJENIS)
-- View: modul/laporan/laporan-penjualan-per-barang.php (PDF)
--       modul/laporan/xls/laporan-penjualan-per-barang.php (Excel)
-- Jalankan sekali pada database aplikasi.
-- ============================================================

-- 1. Definisi laporan
INSERT INTO aareport
  (ARLINK, ARNAME, ARNAME2, ARPAPERORINTED, ARPAPERSIZE, ARACTIVE, ARTITLE,
   ARMARGINLEFT, ARMARGINTOP, ARDATE1F, ARGUDANGF)
VALUES
  ('laporan-penjualan-per-barang', 'Laporan Penjualan Per Barang', 'Laporan Penjualan Per Barang',
   2, 3, 1, 'Laporan Penjualan Per Barang', 6, 6, 1, 1);

SET @arid = LAST_INSERT_ID();

-- 2. Menu di bawah "Laporan Penjualan" (MID 559)
INSERT INTO aamenu
  (MNAMA, MDESCRIPTION, MURUTAN, MPARENT, MTYPE, MACTIVE, MREPORT, MCAPTION1)
VALUES
  ('Laporan Penjualan Per Barang', 'Detail penjualan per barang + jenis merchant',
   197, 559, 1, 1, @arid, 'Laporan Penjualan Per Barang');

SET @mid = LAST_INSERT_ID();

-- 3. Hak akses: copy dari "Laporan Invoice Penjualan"
SET @mid_src = (SELECT MID FROM aamenu WHERE MNAMA = 'Laporan Invoice Penjualan' AND MTYPE = 1 LIMIT 1);

INSERT INTO aausermenu (AUIDUSER, AUIDMENU, AUADD, AUEDIT, AUDELL, AUPRINT, AUAPPROVE)
SELECT AUIDUSER, @mid, AUADD, AUEDIT, AUDELL, 1, 1
  FROM (SELECT DISTINCT AUIDUSER FROM aausermenu WHERE AUIDMENU = @mid_src) src;
