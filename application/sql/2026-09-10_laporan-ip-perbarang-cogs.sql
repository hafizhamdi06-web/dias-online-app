-- ============================================================
-- Laporan "IP Per Barang dan COGS" (sistem aareport)
-- Muncul di menu POS (MPARENT 741), setelah "IP Per Barang" (MID 676 / ARID 173)
-- Layout mengikuti "IP Per Barang" (ARID 173) + 2 kolom COGS:
--   - COGS SDBeli  = qty x fstokd.SDBELI  (harga beli tersimpan di transaksi)
--   - COGS ICOGS   = qty x bitem.ICOGS    (COGS master item)
-- Filter: tanggal, cabang/gudang, item, pelanggan, nomor (sama seperti ARID 173)
-- View: modul/laporan/laporan-ip-perbarang-cogs.php       (PDF, A4 Landscape)
--       modul/laporan/xls/laporan-ip-perbarang-cogs.php   (Excel)
-- Jalankan sekali pada database aplikasi.
-- ============================================================

-- 1. Definisi laporan (flag filter disamakan dengan ARID 173 "IP Perbarang")
INSERT INTO aareport
  (ARLINK, ARNAME, ARNAME2, ARPAPERORINTED, ARPAPERSIZE, ARACTIVE, ARTITLE,
   ARMARGINLEFT, ARMARGINTOP,
   ARDATE1F, ARDATE2F, ARKONTAKF, ARCOAF, ARSOURCEF, ARITEMF, ARSALDOF, ARGUDANGF, ARNOMORF, ARPTF)
VALUES
  ('laporan-ip-perbarang-cogs',
   'IP Per Barang dan COGS', 'IP Per Barang dan COGS',
   2, 3, 1, '', 6, 6,
   1, 0, 1, 0, 0, 1, 0, 1, 1, 0);

SET @arid = LAST_INSERT_ID();

-- 2. Menu di bawah "POS" (MID 741), setelah "IP Per Barang"
SET @urutan = (SELECT COALESCE(MAX(MURUTAN),0) + 1 FROM aamenu WHERE MPARENT = 741);

INSERT INTO aamenu
  (MNAMA, MDESCRIPTION, MURUTAN, MPARENT, MTYPE, MACTIVE, MREPORT, MCAPTION1)
VALUES
  ('IP Per Barang dan COGS', 'IP per barang + kolom COGS (SDBeli & ICOGS)',
   @urutan, 741, 1, 1, @arid, 'IP Per Barang dan COGS');

SET @mid = LAST_INSERT_ID();

-- 3. Hak akses: copy dari menu "IP Per Barang" (MID 676)
INSERT INTO aausermenu (AUIDUSER, AUIDMENU, AUADD, AUEDIT, AUDELL, AUPRINT, AUAPPROVE)
SELECT AUIDUSER, @mid, AUADD, AUEDIT, AUDELL, AUPRINT, AUAPPROVE
  FROM (SELECT * FROM aausermenu WHERE AUIDMENU = 676) src;
