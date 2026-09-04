-- ============================================================
-- Laporan "Laporan IP Per COA 2026 Detail v Agt" (sistem aareport)
-- Muncul di menu Laporan > Laporan Keuangan (MPARENT 557)
-- Turunan dari "Laporan IP Per COA 2026 Detail" (ARID 209) untuk revisi versi Agustus.
-- Filter: tanggal, cabang/gudang, PT, tampil saldo nol
-- View: modul/laporan/laporan-ip-per-coa-2026-detail-v-agt.php (PDF, Letter Landscape)
--       modul/laporan/xls/laporan-ip-per-coa-2026-detail-v-agt.php (Excel)
-- Jalankan sekali pada database aplikasi.
-- ============================================================

-- 1. Definisi laporan
INSERT INTO aareport
  (ARLINK, ARNAME, ARNAME2, ARPAPERORINTED, ARPAPERSIZE, ARACTIVE, ARTITLE,
   ARMARGINLEFT, ARMARGINTOP, ARDATE1F, ARGUDANGF, ARPTF, ARSALDOF)
VALUES
  ('laporan-ip-per-coa-2026-detail-v-agt',
   'Laporan IP Per COA 2026 Detail v Agt', 'Laporan IP Per COA 2026 Detail v Agt',
   2, 1, 1, '', 6, 6, 1, 1, 1, 1);

SET @arid = LAST_INSERT_ID();

-- 2. Menu di bawah "Laporan Keuangan" (MID 557), setelah "Laporan IP Perbulan Versi Agustus 2026" (MURUTAN 246)
INSERT INTO aamenu
  (MNAMA, MDESCRIPTION, MURUTAN, MPARENT, MTYPE, MACTIVE, MREPORT, MCAPTION1)
VALUES
  ('Laporan IP Per COA 2026 Detail v Agt', 'Detail IP per COA 2026 - revisi versi Agustus',
   247, 557, 1, 1, @arid, 'Laporan IP Per COA 2026 Detail v Agt');

SET @mid = LAST_INSERT_ID();

-- 3. Hak akses: copy dari "Laporan IP Per COA 2026 Detail" (MID 716)
SET @mid_src = (SELECT MID FROM aamenu WHERE MNAMA = 'Laporan IP Per COA 2026 Detail' AND MTYPE = 1 LIMIT 1);

INSERT INTO aausermenu (AUIDUSER, AUIDMENU, AUADD, AUEDIT, AUDELL, AUPRINT, AUAPPROVE)
SELECT AUIDUSER, @mid, 0, 0, 0, 1, 1
  FROM (SELECT DISTINCT AUIDUSER FROM aausermenu WHERE AUIDMENU = @mid_src) src;
