-- ============================================================
-- Laporan "Laporan Perhitungan HPP" (sistem aareport)
-- Versi cetak/excel dari menu interaktif "Perhitungan HPP" (Fina),
-- memakai mesin FIFO yg sama (M_Fina_Hpp) supaya angkanya konsisten.
-- Muncul di menu Laporan > Laporan Keuangan (MPARENT 557), sebaris
-- dengan "Laporan IP Penjualan n COGS v Agt".
-- Filter: tanggal (dari/sampai), cabang/gudang, PT.
-- View: modul/laporan/laporan-perhitungan-hpp.php (PDF, A4 Landscape)
--       modul/laporan/xls/laporan-perhitungan-hpp.php (Excel)
-- Jalankan sekali pada database aplikasi.
-- ============================================================

-- 1. Definisi laporan
INSERT INTO aareport
  (ARLINK, ARNAME, ARNAME2, ARPAPERORINTED, ARPAPERSIZE, ARACTIVE, ARTITLE,
   ARMARGINLEFT, ARMARGINTOP, ARDATE1F, ARGUDANGF, ARPTF)
VALUES
  ('laporan-perhitungan-hpp',
   'Laporan Perhitungan HPP', 'Laporan Perhitungan HPP',
   2, 3, 1, '', 6, 6, 1, 1, 1);

SET @arid = LAST_INSERT_ID();

-- 2. Menu di bawah "Laporan Keuangan" (MID 557)
INSERT INTO aamenu
  (MNAMA, MDESCRIPTION, MURUTAN, MPARENT, MTYPE, MACTIVE, MREPORT, MCAPTION1)
VALUES
  ('Laporan Perhitungan HPP', 'Rincian HPP FIFO per item, per transaksi penjualan, termasuk sumber/No PRO',
   249, 557, 1, 1, @arid, 'Laporan Perhitungan HPP');

SET @mid = LAST_INSERT_ID();

-- 3. Hak akses: copy dari "Laporan IP Penjualan n COGS v Agt" (MID 746)
INSERT INTO aausermenu (AUIDUSER, AUIDMENU, AUADD, AUEDIT, AUDELL, AUPRINT, AUAPPROVE)
SELECT AUIDUSER, @mid, 0, 0, 0, 1, 1
  FROM (SELECT DISTINCT AUIDUSER FROM aausermenu WHERE AUIDMENU = 746) src;
