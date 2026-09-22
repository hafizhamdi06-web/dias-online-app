-- ============================================================
-- Menu "DIAS AI" -- chat pemandu terbatas ke laporan yg sudah ada
-- (sistem aareport). User minta data dlm bahasa natural (mis. "data
-- penjualan Juni PT RII dalam bentuk excel"), AI (Claude/Anthropic)
-- mencocokkan ke salah satu laporan yg sudah terdaftar & yg user
-- ybs BERHAK akses (dicek ulang server-side thd aausermenu, bukan
-- cuma percaya keluaran AI), lalu mengisi filter tanggal/PT/cabang-nya.
-- AI TIDAK menyusun query SQL sendiri -- cuma memilih dari katalog
-- laporan yg sudah ada & mengisi parameter filternya.
--
-- Kredensial API disimpan di tabel aaanthropicapi (pola sama dgn
-- aashopeeapi utk integrasi Shopee) -- BUKAN di .env. Jalankan
-- INSERT terpisah dgn api_key Anda sendiri stlh migrasi ini (lihat
-- catatan di akhir file), supaya api_key tdk pernah lewat chat/log.
-- ============================================================

CREATE TABLE IF NOT EXISTS aaanthropicapi (
  id INT NOT NULL AUTO_INCREMENT,
  api_key VARCHAR(255) NOT NULL,
  model VARCHAR(100) NOT NULL DEFAULT 'claude-haiku-4-5-20251001',
  aktif TINYINT(1) NOT NULL DEFAULT 1,
  diubah_oleh INT DEFAULT NULL,
  diubah_pada DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Menu top-level (MPARENT=0), halaman langsung bisa diklik (MTYPE=3),
-- pola sama dgn menu "Data Karyawan".
INSERT INTO aamenu
  (MNAMA, MDESCRIPTION, MURUTAN, MPARENT, MTYPE, MACTIVE, MSHORTNAME, MLINK, MCAPTION1, MICON)
VALUES
  ('DIAS AI', 'Chat pemandu ke laporan yg sudah ada (excel/PDF) pakai bahasa natural',
   243, 0, 3, 1, 'diasai', 'page/dias_ai', 'DIAS AI', 'fas fa-robot');

SET @mid = LAST_INSERT_ID();

-- Hak akses: berikan ke semua user yg sudah pernah login (copy dari daftar user
-- yg ada di aausermenu manapun, distinct) -- krn ini menu baru sama sekali,
-- tdk ada "menu induk" yg relevan utk dicontek hak aksesnya.
INSERT INTO aausermenu (AUIDUSER, AUIDMENU, AUADD, AUEDIT, AUDELL, AUPRINT, AUAPPROVE)
SELECT DISTINCT AUIDUSER, @mid, 0, 0, 0, 0, 1
  FROM aausermenu;

-- ============================================================
-- LANGKAH MANUAL (jalankan terpisah, isi api_key Anda sendiri):
--
-- INSERT INTO aaanthropicapi (api_key, model, aktif)
-- VALUES ('sk-ant-...ISI_API_KEY_ANDA...', 'claude-haiku-4-5-20251001', 1);
-- ============================================================
