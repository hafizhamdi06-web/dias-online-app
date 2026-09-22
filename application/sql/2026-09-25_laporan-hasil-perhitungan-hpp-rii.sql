-- ============================================================
-- Laporan "Laporan Hasil Perhitungan HPP" (sistem aareport)
-- Versi cetak/excel dari data yg SUDAH diproses & tersimpan di tabel
-- fhpprii (hasil menu "Proses HPP PT. RII") -- bukan hitung ulang,
-- tinggal baca dari tabel.
-- Muncul di menu Laporan > Laporan Keuangan (MPARENT 557), sebaris
-- dgn "Laporan Perhitungan HPP".
-- Filter: tanggal (dari/sampai) -- PT sdh pasti RII, tdk perlu filter PT/Cabang.
-- View: modul/laporan/laporan-hasil-perhitungan-hpp.php (PDF, A4 Landscape)
--       modul/laporan/xls/laporan-hasil-perhitungan-hpp.php (Excel)
-- Jalankan sekali pada database aplikasi.
-- ============================================================

-- 1. Definisi laporan
INSERT INTO aareport
  (ARLINK, ARNAME, ARNAME2, ARPAPERORINTED, ARPAPERSIZE, ARACTIVE, ARTITLE,
   ARMARGINLEFT, ARMARGINTOP, ARDATE1F)
VALUES
  ('laporan-hasil-perhitungan-hpp-rii',
   'Laporan Hasil Perhitungan HPP', 'Laporan Hasil Perhitungan HPP (PT. RII)',
   2, 3, 1, '', 6, 6, 1);

SET @arid = LAST_INSERT_ID();

-- 2. Menu di bawah "Laporan Keuangan" (MID 557)
INSERT INTO aamenu
  (MNAMA, MDESCRIPTION, MURUTAN, MPARENT, MTYPE, MACTIVE, MREPORT, MCAPTION1)
VALUES
  ('Laporan Hasil Perhitungan HPP', 'Hasil "Proses HPP PT. RII" yg sudah tersimpan (tabel fhpprii), per item & per transaksi',
   250, 557, 1, 1, @arid, 'Laporan Hasil Perhitungan HPP');

SET @mid = LAST_INSERT_ID();

-- 3. Hak akses: copy dari "Laporan Perhitungan HPP" (MID 757)
INSERT INTO aausermenu (AUIDUSER, AUIDMENU, AUADD, AUEDIT, AUDELL, AUPRINT, AUAPPROVE)
SELECT AUIDUSER, @mid, AUADD, AUEDIT, AUDELL, AUPRINT, AUAPPROVE
  FROM aausermenu WHERE AUIDMENU = 757;
