-- ============================================================
-- Menu "Data Karyawan" (Master Data)
-- Modul CRUD karyawan = kontak bkontak dengan KTIPE = 4 (KARYAWAN)
-- Jalankan sekali pada database aplikasi.
-- ============================================================

-- 1. Daftarkan menu di sidebar (Master Data, sesudah "Data Pasien")
INSERT INTO aamenu
  (MNAMA, MDESCRIPTION, MURUTAN, MPARENT, MTYPE, MACTIVE, MSHORTNAME, MLINK, MCAPTION1, MICON)
VALUES
  ('Data Karyawan', 'Master data karyawan', 44, 0, 3, 1, 'karyawan', 'page/karyawan', 'Data Karyawan', 'fas fa-user-tie');

SET @mid_karyawan = LAST_INSERT_ID();

-- 2. Beri hak akses ke semua user yang saat ini punya akses menu "Data Kontak"
SET @mid_kontak = (SELECT MID FROM aamenu WHERE MLINK = 'page/kontak' LIMIT 1);

INSERT INTO aausermenu (AUIDUSER, AUIDMENU, AUADD, AUEDIT, AUDELL, AUPRINT, AUAPPROVE)
SELECT AUIDUSER, @mid_karyawan, AUADD, AUEDIT, AUDELL, AUPRINT, AUAPPROVE
  FROM (SELECT * FROM aausermenu WHERE AUIDMENU = @mid_kontak) src;
