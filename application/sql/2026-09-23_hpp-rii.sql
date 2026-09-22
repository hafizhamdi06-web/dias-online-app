-- ============================================================
-- Menu "PROSES HPP PT. RII" (Fina)
-- FIFO per lapisan, TAPI di-agregat PER PT (bukan per gudang) --
-- gudang di dalam satu PT dianggap satu "kolam" stok yg sama.
-- Transaksi TMB (terima mutasi) & KMB (kirim mutasi) TIDAK diproses
-- sama sekali (diabaikan), krn cuma perpindahan gudang di dalam PT
-- yg sama -- bukan barang masuk/keluar PT.
--
-- Hasil MASUK HARGA BELI & CARI/PAKAI HARGA BELI disimpan permanen
-- di tabel fhpprii (bukan dihitung ulang tiap kali laporan dibuka),
-- supaya laporan bulanan tinggal baca, dan riwayat proses bisa ditelusuri.
-- Kolom HPLAPORAN=1 menandai baris yg ikut dihitung sbg konsumsi
-- bulanan (SJ, Penyesuaian keluar/PY, Pemakaian Lain/PL) -- bukan
-- baris internal produksi (bahan baku PRO) atau hand-off produksi.
-- ============================================================

CREATE TABLE IF NOT EXISTS fhpprii (
  HPID INT NOT NULL AUTO_INCREMENT,
  HPPT VARCHAR(20) NOT NULL,
  HPSDID INT NOT NULL,
  HPSUID INT NOT NULL,
  HPITEM INT NOT NULL,
  HPIKODE VARCHAR(50) NOT NULL,
  HPINAMA VARCHAR(150) NOT NULL,
  HPTANGGAL DATE NOT NULL,
  HPNOTRANSAKSI VARCHAR(50) NOT NULL,
  HPSUSUMBER VARCHAR(10) NOT NULL,
  HPTIPE VARCHAR(10) NOT NULL,
  HPQTY DOUBLE NOT NULL DEFAULT 0,
  HPHARGA DOUBLE NOT NULL DEFAULT 0,
  HPTOTAL DOUBLE NOT NULL DEFAULT 0,
  HPLAPORAN TINYINT(1) NOT NULL DEFAULT 0,
  HPBULAN TINYINT NOT NULL,
  HPTAHUN SMALLINT NOT NULL,
  HPDIPROSES DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (HPID),
  UNIQUE KEY U_fhpprii_sdid (HPSDID),
  KEY I_fhpprii_periode (HPPT, HPTAHUN, HPBULAN),
  KEY I_fhpprii_item (HPPT, HPITEM)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Menu di bawah Fina (MID 1), sebaris dgn "Perhitungan HPP"
INSERT INTO aamenu
  (MNAMA, MDESCRIPTION, MURUTAN, MPARENT, MTYPE, MACTIVE, MLINK, MICON)
VALUES
  ('Proses HPP PT. RII', 'HPP FIFO per lapisan, diagregat per PT (khusus PT RII), TMB/KMB diabaikan',
   204, 1, 2, 1, 'page/hpp_rii', 'fas fa-industry');

SET @mid = LAST_INSERT_ID();

-- Hak akses: copy dari "Perhitungan HPP"
SET @mid_src = (SELECT MID FROM aamenu WHERE MLINK = 'page/hpp_perhitungan' LIMIT 1);

INSERT INTO aausermenu (AUIDUSER, AUIDMENU, AUADD, AUEDIT, AUDELL, AUPRINT, AUAPPROVE)
SELECT AUIDUSER, @mid, AUADD, AUEDIT, AUDELL, AUPRINT, AUAPPROVE
  FROM aausermenu WHERE AUIDMENU = @mid_src;
