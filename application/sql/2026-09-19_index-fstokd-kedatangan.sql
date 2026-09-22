-- ============================================================
-- Index komposit utk pola "MAX(sdkedatangan) WHERE SDCATATANKOLI=... AND
-- SDIDPOTONGSTOK=..." (dipakai VB TampilkanDetailPaket, dijalankan per
-- item dalam loop paket). Menyertakan SDKEDATANGAN supaya MySQL bisa
-- jawab langsung dari index (covering), tanpa buka baris tabelnya.
-- Melengkapi index I_fstokd_catatankoli / I_fstokd_potongstok yang sudah
-- ada (2026-09-18) -- keduanya tetap dipakai query lain yang cuma
-- filter salah satu kolom saja.
-- ALGORITHM=INPLACE, LOCK=NONE supaya tidak mengunci tabel/mengganggu
-- transaksi POS yang sedang berjalan saat index dibuat.
-- Jalankan sekali pada database aplikasi (aman dijalankan langsung di production).
-- ============================================================

ALTER TABLE fstokd
  ADD INDEX I_fstokd_kedatangan (SDCATATANKOLI, SDIDPOTONGSTOK, SDKEDATANGAN),
  ALGORITHM=INPLACE, LOCK=NONE;
