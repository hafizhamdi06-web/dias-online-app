-- ============================================================
-- Fallback ICOGS di "Proses HPP PT. RII" (fhpprii)
-- Kalau antrian FIFO suatu item kosong saat ada baris keluar yg perlu
-- di-pop, sekarang di-insert baris MASUK darurat (HPSUSUMBER='ICOGS')
-- sejumlah persis qty yg dibutuhkan, harga dari bitem.ICOGS -- supaya
-- baris keluar itu tetap punya harga pokok, bukan Rp 0.
--
-- Baris darurat ini berbagi HPSDID yg sama dgn baris keluar aslinya
-- (krn tdk berasal dari fstokd row sungguhan), jadi UNIQUE KEY lama
-- di HPSDID harus dilonggarkan jadi index biasa.
-- ============================================================

ALTER TABLE fhpprii
  DROP INDEX U_fhpprii_sdid,
  ADD INDEX I_fhpprii_sdid (HPSDID);
