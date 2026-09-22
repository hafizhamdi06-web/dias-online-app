-- ============================================================
-- Laporan "Laporan Nilai Stok" (sistem aareport), berdasarkan hasil
-- "Proses HPP PT. RII" (tabel fhpprii) -- per item: qty & nilai stok
-- per tanggal tertentu (akumulasi seluruh histori s.d. tanggal itu).
-- Muncul di menu Laporan > Laporan Keuangan (MPARENT 557), sebaris
-- dgn laporan HPP RII lainnya.
-- Filter: tanggal (pakai field "Sampai Tanggal" sbg tanggal acuan;
-- field "Dari Tanggal" bawaan filter tidak dipakai, krn laporan ini
-- menampilkan SALDO per satu tanggal, bukan mutasi periode).
-- View: modul/laporan/laporan-nilai-stok-hpp-rii.php (PDF, A4 Landscape)
--       modul/laporan/xls/laporan-nilai-stok-hpp-rii.php (Excel)
-- Jalankan sekali pada database aplikasi.
-- ============================================================

INSERT INTO aareport
  (ARLINK, ARNAME, ARNAME2, ARPAPERORINTED, ARPAPERSIZE, ARACTIVE, ARTITLE,
   ARMARGINLEFT, ARMARGINTOP, ARDATE1F)
VALUES
  ('laporan-nilai-stok-hpp-rii',
   'Laporan Nilai Stok (PT. RII)', 'Laporan Nilai Stok (PT. RII)',
   2, 3, 1, '', 6, 6, 1);

SET @arid = LAST_INSERT_ID();

INSERT INTO aamenu
  (MNAMA, MDESCRIPTION, MURUTAN, MPARENT, MTYPE, MACTIVE, MREPORT, MCAPTION1)
VALUES
  ('Laporan Nilai Stok (PT. RII)', 'Qty & nilai stok per item per tanggal, dari hasil Proses HPP PT. RII',
   253, 557, 1, 1, @arid, 'Laporan Nilai Stok (PT. RII)');

SET @mid = LAST_INSERT_ID();

-- Hak akses: copy dari "Laporan Hasil Perhitungan HPP" (MID 759)
INSERT INTO aausermenu (AUIDUSER, AUIDMENU, AUADD, AUEDIT, AUDELL, AUPRINT, AUAPPROVE)
SELECT AUIDUSER, @mid, AUADD, AUEDIT, AUDELL, AUPRINT, AUAPPROVE
  FROM aausermenu WHERE AUIDMENU = 759;
