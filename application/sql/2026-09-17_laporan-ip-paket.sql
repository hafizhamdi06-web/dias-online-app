-- ============================================================
-- Laporan "IP Paket" (sistem aareport)
-- Menampilkan sisa/pemakaian paket per pasien: dikelompokkan per Nomor
-- Paket (fstokd.SDCATATANKOLI), tiap item di dalam paket menunjukkan
-- Qty Alokasi (epaketd.PDQTY) vs Qty Terpakai (SUM fstokd.SDKELUAR
-- sepanjang umur paket itu, tidak dibatasi periode filter) vs Sisa.
--
-- Relasi dipakai (sesuai permintaan):
--   fstokd.SDIDPOTONGSTOK = epaketu.PUID       (paket mana)
--   fstokd.SDSODURUTAN    = epaketd.PDID       (slot item ke berapa dlm paket)
--   epaketd.PDIDU         = epaketu.PUID
--   fstoku.SUKONTAK       = ID Pasien
--   fstokd.SDCATATANKOLI  = Nomor Paket (nomor instance pembelian paket)
--
-- Periode filter menentukan paket INSTANCE mana yang tampil (yang ada
-- transaksi/pemakaian dlm rentang tsb); Qty Terpakai tetap dihitung
-- sepanjang umur nomor paket itu (bukan dibatasi periode).
--
-- Menu di bawah "POS" (MID 741), setelah "IP Per Barang dan COGS"
-- View: modul/laporan/laporan-ip-paket.php       (PDF, A4 Landscape)
--       modul/laporan/xls/laporan-ip-paket.php   (Excel)
-- Jalankan sekali pada database aplikasi.
-- ============================================================

-- 1. Definisi laporan
--    ARDATE1F=1 (tanggal), ARKONTAKF=1 (pasien), ARGUDANGF=1 (cabang),
--    ARNOMORF=1 (dipakai sbg pencarian sebagian "Nomor Paket", field "Dari Nomor")
INSERT INTO aareport
  (ARLINK, ARNAME, ARNAME2, ARPAPERORINTED, ARPAPERSIZE, ARACTIVE, ARTITLE,
   ARMARGINLEFT, ARMARGINTOP,
   ARDATE1F, ARDATE2F, ARKONTAKF, ARCOAF, ARSOURCEF, ARITEMF, ARSALDOF, ARGUDANGF, ARNOMORF, ARPTF)
VALUES
  ('laporan-ip-paket',
   'IP Paket', 'IP Paket',
   2, 3, 1, '', 6, 6,
   1, 0, 1, 0, 0, 0, 0, 1, 1, 0);

SET @arid = LAST_INSERT_ID();

-- 2. Menu di bawah "POS" (MID 741)
SET @urutan = (SELECT COALESCE(MAX(MURUTAN),0) + 1 FROM aamenu WHERE MPARENT = 741);

INSERT INTO aamenu
  (MNAMA, MDESCRIPTION, MURUTAN, MPARENT, MTYPE, MACTIVE, MREPORT, MCAPTION1)
VALUES
  ('IP Paket', 'Sisa/pemakaian paket per pasien (alokasi vs terpakai per Nomor Paket)',
   @urutan, 741, 1, 1, @arid, 'IP Paket');

SET @mid = LAST_INSERT_ID();

-- 3. Hak akses: copy dari menu "IP Per Barang" (MID 676)
INSERT INTO aausermenu (AUIDUSER, AUIDMENU, AUADD, AUEDIT, AUDELL, AUPRINT, AUAPPROVE)
SELECT AUIDUSER, @mid, AUADD, AUEDIT, AUDELL, AUPRINT, AUAPPROVE
  FROM (SELECT * FROM aausermenu WHERE AUIDMENU = 676) src;
