-- ============================================================
-- Index utk fstokd.SDCATATANKOLI & fstokd.SDIDPOTONGSTOK
-- Dipakai oleh rutin TampilkanDetailPaket() (VB) dan laporan "IP Paket" --
-- keduanya nge-filter fstokd pakai 2 kolom ini, dan sebelumnya TANPA index
-- (full table scan tiap query). ALGORITHM=INPLACE, LOCK=NONE supaya tidak
-- mengunci tabel/menghambat transaksi POS yang sedang berjalan saat index dibuat.
-- Jalankan sekali pada database aplikasi (aman dijalankan langsung di production).
-- ============================================================

ALTER TABLE fstokd
  ADD INDEX I_fstokd_catatankoli (SDCATATANKOLI),
  ADD INDEX I_fstokd_potongstok (SDIDPOTONGSTOK),
  ALGORITHM=INPLACE, LOCK=NONE;
