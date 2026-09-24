-- ============================================================
-- Laporan baru "IP Per Pasien" -- layout sama dgn "IP Per Barang"
-- (ARID 173/MID 676), TAPI dikelompokkan PER PASIEN (H.sukontak),
-- bukan per item. Header blok per pasien: ID Pasien, Nama, No Telp,
-- Sumber (marketing source, join ke tabel blain). Baris detail per
-- pasien: tiap transaksi+item yg dibeli.
-- Ditaruh sebaris dgn "IP Per Barang" di bawah Induk Menu "POS"
-- (MID 741).
-- View: modul/laporan/laporan-ip-per-pasien.php (PDF)
--       modul/laporan/xls/laporan-ip-per-pasien.php (Excel)
-- ============================================================

INSERT INTO aareport
  (ARLINK, ARNAME, ARNAME2, ARPAPERORINTED, ARPAPERSIZE, ARACTIVE, ARTITLE,
   ARMARGINLEFT, ARMARGINTOP, ARDATE1F, ARKONTAKF, ARITEMARRAYF, ARGUDANGF)
VALUES
  ('laporan-ip-per-pasien', 'IP Per Pasien', 'IP Per Pasien',
   1, 1, 1, '', 6, 6, 1, 1, 1, 1);

SET @arid = LAST_INSERT_ID();

INSERT INTO aamenu
  (MNAMA, MDESCRIPTION, MURUTAN, MPARENT, MTYPE, MACTIVE, MREPORT, MCAPTION1)
VALUES
  ('IP Per Pasien', 'Penjualan IP/Alkes dikelompokkan per pasien, termasuk sumber marketing',
   8, 741, 1, 1, @arid, 'IP Per Pasien');

SET @mid = LAST_INSERT_ID();

-- Hak akses: copy dari "IP Per Barang" (MID 676)
INSERT INTO aausermenu (AUIDUSER, AUIDMENU, AUADD, AUEDIT, AUDELL, AUPRINT, AUAPPROVE)
SELECT AUIDUSER, @mid, AUADD, AUEDIT, AUDELL, AUPRINT, AUAPPROVE
  FROM aausermenu WHERE AUIDMENU = 676;
