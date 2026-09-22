-- ============================================================
-- "Laporan Nilai Stok (PT. RII)" (ARID 227 / MID 761): ganti filter
-- dari pasangan Dari-Sampai Tanggal (ARDATE1F) jadi SATU field
-- "Per Tanggal" (ARDATE2F) -- pola yg sama dgn laporan-neraca dkk,
-- krn laporan ini menampilkan SALDO per satu tanggal (sutanggal <=
-- tanggal), bukan mutasi periode.
-- ============================================================

UPDATE aareport
   SET ARDATE1F = 0,
       ARDATE2F = 1
 WHERE ARID = 227;
