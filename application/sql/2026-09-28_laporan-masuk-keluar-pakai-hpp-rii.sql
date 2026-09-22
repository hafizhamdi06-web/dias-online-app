-- ============================================================
-- "Laporan Item Masuk dan Keluar" (ARID 205 / MID 711) sekarang
-- membaca harga masuk & keluar dari hasil "Proses HPP PT. RII"
-- (tabel fhpprii, HPHARGA) -- bukan harga transaksi mentah
-- (fstokd.SDHARGA-SDDISKON) seperti versi pertama.
--
-- Karena fhpprii cuma utk PT RII & tdk per-gudang (diagregat per PT),
-- filter Cabang (ARGUDANGF) & PT (ARPTF) sdh tdk relevan lagi --
-- dimatikan supaya formulir laporan tdk menampilkan pilihan yg tdk
-- dipakai. Filter yg tersisa cuma tanggal (ARDATE1F).
-- ============================================================

UPDATE aareport
   SET ARGUDANGF = 0,
       ARPTF = 0
 WHERE ARID = 205;
