-- ============================================================
-- Laporan "Laporan IP Penjualan n COGS v Agt" (sistem aareport)
-- Muncul di menu Laporan > Laporan Keuangan (MPARENT 557)
-- Layout mengikuti "Laporan IP Perbulan versi 25 % COA Baru" (ARID 204),
-- sebagai basis untuk revisi versi Agustus.
-- Filter: tanggal, cabang/gudang, PT, tampil saldo nol
-- View: modul/laporan/laporan-ip-penjualan-cogs-v-agt.php (PDF, Letter Landscape)
--       modul/laporan/xls/laporan-ip-penjualan-cogs-v-agt.php (Excel)
-- Jalankan sekali pada database aplikasi.
-- ============================================================

-- 1. Definisi laporan
INSERT INTO aareport
  (ARLINK, ARNAME, ARNAME2, ARPAPERORINTED, ARPAPERSIZE, ARACTIVE, ARTITLE,
   ARMARGINLEFT, ARMARGINTOP, ARDATE1F, ARGUDANGF, ARPTF, ARSALDOF)
VALUES
  ('laporan-ip-penjualan-cogs-v-agt',
   'Laporan IP Penjualan n COGS v Agt', 'Laporan IP Penjualan n COGS v Agt',
   2, 1, 1, '', 6, 6, 1, 1, 1, 1);

SET @arid = LAST_INSERT_ID();

-- 2. Menu di bawah "Laporan Keuangan" (MID 557)
INSERT INTO aamenu
  (MNAMA, MDESCRIPTION, MURUTAN, MPARENT, MTYPE, MACTIVE, MREPORT, MCAPTION1)
VALUES
  ('Laporan IP Penjualan n COGS v Agt', 'IP penjualan vs COGS - revisi versi Agustus',
   248, 557, 1, 1, @arid, 'Laporan IP Penjualan n COGS v Agt');

SET @mid = LAST_INSERT_ID();

-- 3. Hak akses: copy dari "Laporan IP Perbulan versi 25 % COA Baru" (MID 710)
SET @mid_src = (SELECT MID FROM aamenu WHERE MNAMA = 'Laporan IP Perbulan versi 25 % COA Baru' AND MTYPE = 1 LIMIT 1);

INSERT INTO aausermenu (AUIDUSER, AUIDMENU, AUADD, AUEDIT, AUDELL, AUPRINT, AUAPPROVE)
SELECT AUIDUSER, @mid, 0, 0, 0, 1, 1
  FROM (SELECT DISTINCT AUIDUSER FROM aausermenu WHERE AUIDMENU = @mid_src) src;
