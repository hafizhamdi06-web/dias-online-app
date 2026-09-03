-- ============================================================
-- Laporan "Laporan IP DP Per Tanggal" (sistem aareport)
-- Menu Laporan > Laporan Penjualan
-- Sumber: view v_data_dp, difilter "Per Tanggal": V.tanggal_transaksi <= tanggal
-- Grup: Cabang (bgudang.gnama)
-- Kolom: Tanggal | Kode Pasien | Nama Pelanggan | No Transaksi | No DP | Nilai
-- View: modul/laporan/laporan-ip-dp-pertanggal.php (PDF, A4 Portrait)
--       modul/laporan/xls/laporan-ip-dp-pertanggal.php (Excel)
-- Jalankan sekali pada database aplikasi.
-- ============================================================

INSERT INTO aareport
  (ARLINK, ARNAME, ARNAME2, ARPAPERORINTED, ARPAPERSIZE, ARACTIVE, ARTITLE,
   ARMARGINLEFT, ARMARGINTOP, ARDATE2F, ARGUDANGF)
VALUES
  ('laporan-ip-dp-pertanggal', 'Laporan IP DP Per Tanggal',
   'Laporan IP DP Per Tanggal', 1, 3, 1,
   'Laporan IP DP Per Tanggal', 6, 6, 1, 1);

SET @arid = LAST_INSERT_ID();

INSERT INTO aamenu
  (MNAMA, MDESCRIPTION, MURUTAN, MPARENT, MTYPE, MACTIVE, MREPORT, MCAPTION1)
VALUES
  ('Laporan IP DP Per Tanggal', 'Daftar DP IP per cabang (v_data_dp)',
   200, 559, 1, 1, @arid, 'Laporan IP DP Per Tanggal');

SET @mid = LAST_INSERT_ID();

SET @mid_src = (SELECT MID FROM aamenu WHERE MNAMA = 'Laporan Invoice Penjualan' AND MTYPE = 1 LIMIT 1);

INSERT INTO aausermenu (AUIDUSER, AUIDMENU, AUADD, AUEDIT, AUDELL, AUPRINT, AUAPPROVE)
SELECT AUIDUSER, @mid, 0, 0, 0, 1, 1
  FROM (SELECT DISTINCT AUIDUSER FROM aausermenu WHERE AUIDMENU = @mid_src) src;
