-- ============================================================
-- Rename "Laporan Item Masuk dan Keluar" (MID 711 / ARID 205) jadi
-- "Laporan Hasil Perhitungan HPP (Stok Masuk dan Keluar)", & pindahkan
-- dari grup "Laporan Persediaan" (MPARENT 560) ke "Laporan Keuangan"
-- (MPARENT 557), sebaris dgn laporan HPP RII lainnya -- krn sejak
-- 2026-09-28 laporan ini sudah membaca harga dari hasil "Proses HPP
-- PT. RII" (fhpprii), bukan lagi laporan stok umum.
-- ============================================================

-- CATATAN: MNAMA/ARNAME/ARNAME2 semua varchar(50) -- nama panjang
-- "Laporan Hasil Perhitungan HPP (Stok Masuk dan Keluar)" (54 karakter)
-- kepotong diam2 kalau dipaksakan, jadi dipendekkan sedikit spy pas (49 karakter).
UPDATE aamenu
   SET MNAMA = 'Laporan Hasil Perhitungan HPP (Stok Masuk-Keluar)',
       MCAPTION1 = 'Laporan Hasil Perhitungan HPP (Stok Masuk dan Keluar)',
       MPARENT = 557,
       MURUTAN = 252
 WHERE MID = 711;

UPDATE aareport
   SET ARNAME = 'Laporan Hasil Perhitungan HPP (Stok Masuk-Keluar)',
       ARNAME2 = 'Laporan Hasil Perhitungan HPP (Stok Masuk-Keluar)'
 WHERE ARID = 205;
