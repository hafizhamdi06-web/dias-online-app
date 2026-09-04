-- ============================================================
-- Laporan "Laporan COGS Versi Agustus 2026" (sistem aareport)
-- Muncul di menu Laporan > Laporan Keuangan (MPARENT 557)
-- Filter: tanggal, cabang/gudang, PT, tampil saldo nol
-- View: modul/laporan/laporan-fina-rekap-sales-cogs-versi-agustus-2026.php (PDF, A4/Letter Landscape)
-- Jalankan sekali pada database aplikasi.
-- ============================================================

-- 1. Definisi laporan
INSERT INTO aareport
  (ARLINK, ARNAME, ARNAME2, ARPAPERORINTED, ARPAPERSIZE, ARACTIVE, ARTITLE,
   ARMARGINLEFT, ARMARGINTOP, ARDATE1F, ARGUDANGF, ARPTF, ARSALDOF)
VALUES
  ('laporan-fina-rekap-sales-cogs-versi-agustus-2026',
   'Laporan COGS Versi Agustus 2026', 'Laporan COGS Versi Agustus 2026',
   2, 1, 1, '', 6, 6, 1, 1, 1, 1);

SET @arid = LAST_INSERT_ID();

-- 2. Menu di bawah "Laporan Keuangan" (MID 557), setelah "Laporan COGS V5" (MURUTAN 239)
INSERT INTO aamenu
  (MNAMA, MDESCRIPTION, MURUTAN, MPARENT, MTYPE, MACTIVE, MREPORT, MCAPTION1)
VALUES
  ('Laporan COGS Versi Agustus 2026', 'Rekap Sales vs COGS versi Agustus 2026',
   240, 557, 1, 1, @arid, 'Laporan COGS Versi Agustus 2026');

SET @mid = LAST_INSERT_ID();

-- 3. Hak akses: copy dari "Laporan COGS V5" (MID 715)
SET @mid_src = (SELECT MID FROM aamenu WHERE MNAMA = 'Laporan COGS V5' AND MTYPE = 1 LIMIT 1);

INSERT INTO aausermenu (AUIDUSER, AUIDMENU, AUADD, AUEDIT, AUDELL, AUPRINT, AUAPPROVE)
SELECT AUIDUSER, @mid, 0, 0, 0, 1, 1
  FROM (SELECT DISTINCT AUIDUSER FROM aausermenu WHERE AUIDMENU = @mid_src) src;
