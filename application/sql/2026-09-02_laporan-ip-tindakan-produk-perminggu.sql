-- ============================================================
-- Laporan "Laporan IP Tindakan/Produk Per Minggu" (sistem aareport)
-- Muncul di menu Laporan > Laporan Penjualan
-- Sumber: fstoku.SUSUMBER IN ('IP','AL'), SUSTATUS <> 9
-- Grup: Cabang > Minggu (Senin-Minggu) > Tindakan/Produk
-- Kolom: Tindakan/Produk | Qty Transaksi | Nilai | Pasien (unik)
-- View: modul/laporan/laporan-ip-tindakan-produk-perminggu.php (PDF, A4 Portrait)
--       modul/laporan/xls/laporan-ip-tindakan-produk-perminggu.php (Excel)
-- Jalankan sekali pada database aplikasi.
-- ============================================================

INSERT INTO aareport
  (ARLINK, ARNAME, ARNAME2, ARPAPERORINTED, ARPAPERSIZE, ARACTIVE, ARTITLE,
   ARMARGINLEFT, ARMARGINTOP, ARDATE1F, ARGUDANGF)
VALUES
  ('laporan-ip-tindakan-produk-perminggu', 'Laporan IP Tindakan/Produk Per Minggu',
   'Laporan IP Tindakan/Produk Per Minggu', 1, 3, 1,
   'Laporan IP Tindakan/Produk Per Minggu', 6, 6, 1, 1);

SET @arid = LAST_INSERT_ID();

INSERT INTO aamenu
  (MNAMA, MDESCRIPTION, MURUTAN, MPARENT, MTYPE, MACTIVE, MREPORT, MCAPTION1)
VALUES
  ('Laporan IP Tindakan/Produk Per Minggu', 'Rekap qty, nilai & pasien per minggu (IP + AL)',
   198, 559, 1, 1, @arid, 'Laporan IP Tindakan/Produk Per Minggu');

SET @mid = LAST_INSERT_ID();

SET @mid_src = (SELECT MID FROM aamenu WHERE MNAMA = 'Laporan Invoice Penjualan' AND MTYPE = 1 LIMIT 1);

INSERT INTO aausermenu (AUIDUSER, AUIDMENU, AUADD, AUEDIT, AUDELL, AUPRINT, AUAPPROVE)
SELECT AUIDUSER, @mid, 0, 0, 0, 1, 1
  FROM (SELECT DISTINCT AUIDUSER FROM aausermenu WHERE AUIDMENU = @mid_src) src;
