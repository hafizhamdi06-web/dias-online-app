-- ============================================================
-- Menu "Data Pasien" (Master Data)
-- Modul CRUD pasien = kontak bkontak dengan KTIPE = 24 (PASIEN)
-- Jalankan sekali pada database aplikasi.
-- ============================================================

-- 1. Daftarkan menu di sidebar (Master Data, setelah "Data Item POS")
INSERT INTO aamenu
  (MNAMA, MDESCRIPTION, MURUTAN, MPARENT, MTYPE, MACTIVE, MSHORTNAME, MLINK, MCAPTION1, MICON)
VALUES
  ('Data Pasien', 'Master data pasien', 44, 0, 3, 1, 'pasien', 'page/pasien', 'Data Pasien', 'fas fa-user-injured');

SET @mid_pasien = LAST_INSERT_ID();

-- 2. Beri hak akses ke semua user yang saat ini punya akses menu "Data Kontak" (MID 587)
SET @mid_kontak = (SELECT MID FROM aamenu WHERE MLINK = 'page/kontak' LIMIT 1);

INSERT INTO aausermenu (AUIDUSER, AUIDMENU, AUADD, AUEDIT, AUDELL, AUPRINT, AUAPPROVE)
SELECT AUIDUSER, @mid_pasien, AUADD, AUEDIT, AUDELL, AUPRINT, AUAPPROVE
  FROM (SELECT * FROM aausermenu WHERE AUIDMENU = @mid_kontak) src;
