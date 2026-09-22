-- ============================================================
-- "Sumber HPP" utk Proses HPP PT. RII: rincian lapisan FIFO yg
-- dipakai utk membentuk HPP tiap baris keluar (SJ/PY-keluar/PL),
-- supaya bisa ditelusuri "HPP segini berasal dari harga beli yg mana".
--
-- Satu baris keluar (fhpprii, HPSDID) bisa dibentuk dari >1 lapisan
-- (kalau satu lapisan tdk cukup), makanya perlu tabel anak tersendiri.
-- Diisi ulang bareng fhpprii tiap kali "Proses Ulang" dijalankan.
-- ============================================================

CREATE TABLE IF NOT EXISTS fhpprii_asal (
  HAID INT NOT NULL AUTO_INCREMENT,
  HAPT VARCHAR(20) NOT NULL,
  HASDID INT NOT NULL,
  HAURUTAN INT NOT NULL,
  HASUSUMBER VARCHAR(20) NOT NULL,
  HANOTRANSAKSI VARCHAR(50) NOT NULL,
  HATANGGAL DATE NOT NULL,
  HAQTY DOUBLE NOT NULL DEFAULT 0,
  HAHARGA DOUBLE NOT NULL DEFAULT 0,
  PRIMARY KEY (HAID),
  KEY I_fhpprii_asal_sdid (HASDID),
  KEY I_fhpprii_asal_pt (HAPT)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
