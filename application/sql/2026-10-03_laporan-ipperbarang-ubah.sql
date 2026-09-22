-- ============================================================
-- "IP Perbarang" (ARID 173):
-- 1. Kolom Kasir dihapus dari laporan, Nama Pelanggan otomatis geser
--    ke posisinya, kolom baru "No HP Pelanggan" ditambahkan sesudah
--    Nama Pelanggan (lihat perubahan view: laporan-ipperbarang.php
--    & xls/laporan-ipperbarang.php).
-- 2. Filter Item diganti dari single-select (ARITEMF) jadi multi-
--    select/array (ARITEMARRAYF, kolom baru) -- bisa pilih banyak
--    item sekaligus. ARITEMARRAYF kolom baru krn ARITEMF dipakai 5
--    laporan lain yg masih single-select, tidak boleh diubah global.
-- ============================================================

ALTER TABLE aareport ADD COLUMN ARITEMARRAYF TINYINT(1) NOT NULL DEFAULT 0 AFTER ARITEMF;

UPDATE aareport
   SET ARITEMF = 0,
       ARITEMARRAYF = 1
 WHERE ARID = 173;
