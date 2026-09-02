-- ============================================================
-- Laporan "Laporan IP Kedatangan Pasien" (sistem aareport)
-- Menu Laporan > Laporan Penjualan
-- Sumber: fstoku.SUSUMBER IN ('IP','AL'), SUSTATUS <> 9
-- 1 baris per pasien per cabang. Kolom: No, Nama Pasien, Tgl Lahir,
--   Id Pasien, Cabang, Kedatangan (jml hari kunjungan),
--   Sub Total (SUM sutotaltransaksi), Kedatangan Terakhir (MAX tanggal),
--   No Telp, JK, Kecamatan, Kota
-- View: modul/laporan/laporan-ip-kedatangan-pasien.php (PDF, A4 Landscape)
--       modul/laporan/xls/laporan-ip-kedatangan-pasien.php (Excel)
-- Jalankan sekali pada database aplikasi.
-- ============================================================

INSERT INTO aareport
  (ARLINK, ARNAME, ARNAME2, ARPAPERORINTED, ARPAPERSIZE, ARACTIVE, ARTITLE,
   ARMARGINLEFT, ARMARGINTOP, ARDATE1F, ARGUDANGF)
VALUES
  ('laporan-ip-kedatangan-pasien', 'Laporan IP Kedatangan Pasien',
   'Laporan IP Kedatangan Pasien', 2, 3, 1,
   'Laporan IP Kedatangan Pasien', 6, 6, 1, 1);

SET @arid = LAST_INSERT_ID();

INSERT INTO aamenu
  (MNAMA, MDESCRIPTION, MURUTAN, MPARENT, MTYPE, MACTIVE, MREPORT, MCAPTION1)
VALUES
  ('Laporan IP Kedatangan Pasien', 'Rekap kedatangan pasien per cabang (IP + AL)',
   199, 559, 1, 1, @arid, 'Laporan IP Kedatangan Pasien');

SET @mid = LAST_INSERT_ID();

SET @mid_src = (SELECT MID FROM aamenu WHERE MNAMA = 'Laporan Invoice Penjualan' AND MTYPE = 1 LIMIT 1);

INSERT INTO aausermenu (AUIDUSER, AUIDMENU, AUADD, AUEDIT, AUDELL, AUPRINT, AUAPPROVE)
SELECT AUIDUSER, @mid, 0, 0, 0, 1, 1
  FROM (SELECT DISTINCT AUIDUSER FROM aausermenu WHERE AUIDMENU = @mid_src) src;
