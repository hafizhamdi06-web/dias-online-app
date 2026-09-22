-- ============================================================
-- Menu "Perhitungan HPP" di bawah "Fina" (MID 1)
-- Menghitung Harga Pokok Penjualan (HPP) metode FIFO per PT/Cabang/Bulan.
-- Controller : Fina_Hpp.php   Model: M_Fina_Hpp.php
-- Halaman    : page/hpp_perhitungan
-- Jalankan sekali pada database aplikasi.
-- ============================================================

SET @urutan = (SELECT COALESCE(MAX(MURUTAN),0) + 1 FROM aamenu WHERE MPARENT = 1);

INSERT INTO aamenu
  (MNAMA, MDESCRIPTION, MURUTAN, MPARENT, MTYPE, MACTIVE, MSHORTNAME, MLINK, MCAPTION1, MICON)
VALUES
  ('Perhitungan HPP', 'Hitung Harga Pokok Penjualan (FIFO) per PT/Cabang/Bulan',
   @urutan, 1, 2, 1, 'hpp_perhitungan', 'page/hpp_perhitungan', 'Perhitungan HPP', 'fas fa-calculator');

SET @mid = LAST_INSERT_ID();

-- Hak akses: copy dari menu "Jurnal Umum" (MID 24) di grup Fina yg sama
INSERT INTO aausermenu (AUIDUSER, AUIDMENU, AUADD, AUEDIT, AUDELL, AUPRINT, AUAPPROVE)
SELECT AUIDUSER, @mid, AUADD, AUEDIT, AUDELL, AUPRINT, AUAPPROVE
  FROM (SELECT * FROM aausermenu WHERE AUIDMENU = 24) src;
