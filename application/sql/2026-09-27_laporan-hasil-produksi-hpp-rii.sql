-- ============================================================
-- Laporan "Laporan Hasil Produksi" (sistem aareport)
-- Berbeda orientasi dgn "Laporan Hasil Perhitungan HPP" (yg berbasis
-- item TERJUAL) -- laporan ini berbasis TRANSAKSI PRODUKSI: apa yg
-- DIHASILKAN tiap transaksi PRO & berapa HPP per unitnya, dikelompokkan
-- per No Transaksi PRO. Sumber data sama (tabel fhpprii, hasil "Proses
-- HPP PT. RII"), tinggal query beda, tanpa mesin hitung baru.
-- Muncul di menu Laporan > Laporan Keuangan (MPARENT 557), sebaris
-- dgn "Laporan Hasil Perhitungan HPP".
-- Filter: tanggal (dari/sampai).
-- View: modul/laporan/laporan-hasil-produksi-hpp-rii.php (PDF, A4 Landscape)
--       modul/laporan/xls/laporan-hasil-produksi-hpp-rii.php (Excel)
-- Jalankan sekali pada database aplikasi.
-- ============================================================

-- 1. Definisi laporan
INSERT INTO aareport
  (ARLINK, ARNAME, ARNAME2, ARPAPERORINTED, ARPAPERSIZE, ARACTIVE, ARTITLE,
   ARMARGINLEFT, ARMARGINTOP, ARDATE1F)
VALUES
  ('laporan-hasil-produksi-hpp-rii',
   'Laporan Hasil Produksi', 'Laporan Hasil Produksi (PT. RII)',
   2, 3, 1, '', 6, 6, 1);

SET @arid = LAST_INSERT_ID();

-- 2. Menu di bawah "Laporan Keuangan" (MID 557)
INSERT INTO aamenu
  (MNAMA, MDESCRIPTION, MURUTAN, MPARENT, MTYPE, MACTIVE, MREPORT, MCAPTION1)
VALUES
  ('Laporan Hasil Produksi', 'Apa yg dihasilkan tiap transaksi PRO & HPP per unitnya (PT. RII)',
   251, 557, 1, 1, @arid, 'Laporan Hasil Produksi');

SET @mid = LAST_INSERT_ID();

-- 3. Hak akses: copy dari "Laporan Hasil Perhitungan HPP" (MID 759)
INSERT INTO aausermenu (AUIDUSER, AUIDMENU, AUADD, AUEDIT, AUDELL, AUPRINT, AUAPPROVE)
SELECT AUIDUSER, @mid, AUADD, AUEDIT, AUDELL, AUPRINT, AUAPPROVE
  FROM aausermenu WHERE AUIDMENU = 759;
