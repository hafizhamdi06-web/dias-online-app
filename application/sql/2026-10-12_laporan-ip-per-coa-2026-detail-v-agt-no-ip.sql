INSERT INTO aareport
  (ARLINK, ARNAME, ARNAME2, ARPAPERORINTED, ARPAPERSIZE, ARACTIVE, ARTITLE,
   ARMARGINLEFT, ARMARGINTOP, ARDATE1F, ARGUDANGF, ARPTF, ARSALDOF)
VALUES
  ('laporan-ip-per-coa-2026-detail-v-agt-no-ip',
   'Laporan IP Per COA 2026 Detail v Agt (Per No IP)',
   'Laporan IP Per COA 2026 Detail v Agt (Per No IP)',
   2, 1, 1, '', 6, 6, 1, 1, 1, 0);

SET @arid = LAST_INSERT_ID();

INSERT INTO aamenu
  (MNAMA, MDESCRIPTION, MURUTAN, MPARENT, MTYPE, MACTIVE, MREPORT, MCAPTION1)
VALUES
  ('Laporan IP Per COA 2026 Detail v Agt (Per No IP)',
   'Copy dari Laporan IP Per COA 2026 Detail v Agt, didetailkan sampai No IP (no transaksi)',
   251, 557, 1, 1, @arid, 'Laporan IP Per COA 2026 Detail v Agt (Per No IP)');

SET @mid = LAST_INSERT_ID();

-- Hak akses: copy dari "Laporan IP Per COA 2026 Detail v Agt" (MID 745)
INSERT INTO aausermenu (AUIDUSER, AUIDMENU, AUADD, AUEDIT, AUDELL, AUPRINT, AUAPPROVE)
SELECT AUIDUSER, @mid, AUADD, AUEDIT, AUDELL, AUPRINT, AUAPPROVE
  FROM aausermenu WHERE AUIDMENU = 745;
