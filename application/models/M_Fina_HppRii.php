<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Proses HPP PT. RII -- metode FIFO per lapisan, TAPI diagregat PER PT
 * (bukan per gudang seperti M_Fina_Hpp/"Perhitungan HPP"). Semua gudang
 * di dalam PT yg sama dianggap satu "kolam" stok bersama, karena
 * SUSUMBER='TMB' (terima mutasi) dan 'KMB' (kirim mutasi) SAMA SEKALI
 * TIDAK diproses -- itu cuma perpindahan gudang di dalam PT yg sama,
 * bukan barang masuk/keluar PT.
 *
 * Baris MASUK (dorong lapisan baru, "MASUK HARGA BELI"):
 *   PB  (Pembelian)          -> harga = SDHARGA - SDDISKON
 *   PY  (Penyesuaian, SDMASUK>0)  -> harga = 0
 *   PBC (Penerimaan Barang Cabang) -> harga = 0
 *   PRO (Produksi, barang jadi)    -> harga = akumulasi HPP bahan baku
 *                                     (SDKELUAR SDURUTAN < SDURUTAN baris masuk ini) / qty masuk
 *   TMB -> TIDAK DIPROSES (skip total)
 *
 * Baris KELUAR (ambil/pop dari lapisan FIFO, "CARI HARGA BELI"):
 *   SJ (Surat Jalan), PY (Penyesuaian, SDKELUAR>0), PL (Pemakaian Lain)
 *      -> pop antrian, DICATAT sbg konsumsi bulanan (HPLAPORAN=1)
 *   PRO (bahan baku) -> pop antrian, HPP-nya masuk pool utk baris masuk
 *      barang jadi di transaksi yg sama, TIDAK dicatat sbg konsumsi bulanan
 *   PRO (hand-off: barang jadi keluar dgn item & qty SAMA persis dgn baris
 *      masuk barang jadi barusan di transaksi yg sama) -> SAMA SEKALI TIDAK
 *      pop antrian (bukan bahan baku, bukan penjualan -- barang itu blm
 *      benar2 keluar dari PT, cuma serah-terima internal hasil produksi)
 *   KMB -> TIDAK DIPROSES (skip total)
 *
 * Kalau antrian KOSONG saat ada baris keluar yg perlu di-pop (item itu blm
 * pernah punya transaksi masuk yg tercatat di fhpprii), HPP-nya dianggap
 * Rp 0 -- SEMUA harga HARUS berdasar transaksi masuk sungguhan (PB/PY/PBC/
 * PRO), tidak ada fallback ke harga master item (ICOGS/IHARGABELI2/dsb).
 *
 * Hasil setiap baris disimpan permanen ke tabel fhpprii (lihat
 * application/sql/2026-09-23_hpp-rii.sql), supaya laporan bulanan
 * tinggal baca dari situ tanpa perlu proses ulang tiap kali dibuka.
 */
class M_Fina_HppRii extends CI_Model {

    private $susumberKeluarLaporan = array('SJ', 'PY', 'PL');

    function __construct()
    {
        parent::__construct();
    }

    function getPt()
    {
        $row = $this->db->query("SELECT npid FROM bnamapt WHERE npnama='PT. RII' LIMIT 1")->row();
        return $row ? $row->npid : null;
    }

    // Proses ulang histori PT RII dari $tglAwal (opsional, kosongkan utk dari awal data)
    // s.d. $tglAkhir (YYYY-MM-DD), simpan ke fhpprii. Data lama utk PT ini dihapus dulu
    // spy tidak ada baris dobel/basi.
    //
    // CATATAN kalau $tglAwal diisi: antrian FIFO tiap item MULAI KOSONG persis di tanggal
    // itu -- stok yg "dibawa" dari sblm tglAwal TIDAK ikut kehitung. Jadi transaksi keluar
    // yg terjadi tdk lama stlh tglAwal (sblm ada transaksi masuk baru) bisa kepotong Rp 0
    // krn antriannya blm sempat terisi lapisan baru.
    function prosesUlang($tglAkhir, $tglAwal = null)
    {
        $pt  = $this->_ptWajib();
        $asalLog = array();
        $log = $this->_hitungLog($pt, $tglAkhir, $tglAwal, $asalLog);

        $this->db->trans_begin();
        $this->db->where('HPPT', $pt)->delete('fhpprii');
        foreach (array_chunk($log, 500) as $chunk) {
            $this->db->insert_batch('fhpprii', $chunk);
        }
        $this->db->where('HAPT', $pt)->delete('fhpprii_asal');
        foreach (array_chunk($asalLog, 500) as $chunk) {
            $this->db->insert_batch('fhpprii_asal', $chunk);
        }
        $this->db->trans_commit();

        return count($log);
    }

    private function _ptWajib()
    {
        $pt = $this->getPt();
        if (!$pt) {
            throw new \Exception('PT. RII tidak ditemukan di master PT (bnamapt).');
        }
        return $pt;
    }

    // Jalankan simulasi FIFO (SAMA PERSIS dgn yg dipakai prosesUlang()) dan kembalikan
    // baris2 yg SEHARUSNYA tersimpan di fhpprii -- dipakai jg oleh cekStatus() spy tdk ada
    // duplikasi logika (termasuk aturan hand-off yg TIDAK menghasilkan baris log).
    // $asalLog (by reference) diisi rincian lapisan FIFO yg dipakai utk tiap baris keluar
    // yg HPLAPORAN=1 -- utk fitur "sumber HPP" (lihat asalTransaksi()).
    private function _hitungLog($pt, $tglAkhir, $tglAwal = null, &$asalLog = array())
    {
        $query = "SELECT D.sdid 'sdid', H.suid 'suid', H.sunotransaksi 'sunotransaksi', H.susumber 'susumber',
                         H.sutanggal 'sutanggal', D.sdurutan 'sdurutan', D.sditem 'sditem',
                         I.ikode 'ikode', I.inama 'inama',
                         IFNULL(D.sdmasuk,0) 'sdmasuk', IFNULL(D.sdkeluar,0) 'sdkeluar',
                         IFNULL(D.sdharga,0) 'sdharga', IFNULL(D.sddiskon,0) 'sddiskon'
                    FROM fstokd D
              INNER JOIN fstoku H ON H.suid = D.sdidsu
              INNER JOIN bgudang G ON G.gid = D.sdgudang
              INNER JOIN bitem  I ON I.iid  = D.sditem
                   WHERE H.sustatus <> 9
                     AND G.gpt = '".$this->db->escape_str($pt)."'
                     AND H.sutanggal <= '".$this->db->escape_str($tglAkhir)."'".
                     (!empty($tglAwal) ? " AND H.sutanggal >= '".$this->db->escape_str($tglAwal)."'" : "")."
                     AND H.susumber NOT IN ('TMB', 'KMB')
                     AND (D.sdmasuk > 0 OR D.sdkeluar > 0)
                ORDER BY H.sutanggal, H.suid, D.sdurutan";

        $rows = $this->db->query($query)->result();

        $antrian = array(); // sditem => array of ['qty'=>,'harga'=>]
        $log     = array(); // baris yg akan disimpan ke fhpprii

        $suidProAktif     = null;
        $biayaPool        = 0.0;
        $masukTerakhirPro = null; // ['item'=>, 'qty'=>]

        foreach ($rows as $r) {
            $item = $r->sditem;
            if (!isset($antrian[$item])) $antrian[$item] = array();

            if ($r->susumber === 'PRO' && $r->suid !== $suidProAktif) {
                $suidProAktif     = $r->suid;
                $biayaPool        = 0.0;
                $masukTerakhirPro = null;
            }

            if ($r->sdmasuk > 0) {
                $qty = (float) $r->sdmasuk;

                if ($r->susumber === 'PRO') {
                    $harga = ($biayaPool > 0) ? ($biayaPool / $qty) : 0.0;
                    $masukTerakhirPro = array('item' => $item, 'qty' => $qty);
                    $biayaPool = 0.0;
                } elseif ($r->susumber === 'PB') {
                    $harga = (float) $r->sdharga - (float) $r->sddiskon;
                    if ($harga < 0) $harga = 0.0;
                } else {
                    // PY (penyesuaian masuk), PBC, atau lainnya -> harga = 0
                    $harga = 0.0;
                }

                $antrian[$item][] = array(
                    'qty' => $qty, 'harga' => $harga,
                    'susumber' => $r->susumber, 'notransaksi' => $r->sunotransaksi, 'tanggal' => $r->sutanggal,
                );

                $log[] = $this->_baris($r, 'MASUK', $qty, $harga, $qty * $harga, 0);
                continue;
            }

            if ($r->sdkeluar <= 0) continue;

            $qty = (float) $r->sdkeluar;

            // hand-off: barang jadi PRO keluar lg dgn item & qty SAMA persis dgn masuk barusan
            $handoff = ($r->susumber === 'PRO' && $masukTerakhirPro !== null
                        && $masukTerakhirPro['item'] === $item
                        && abs($masukTerakhirPro['qty'] - $qty) < 0.0000001);

            if ($handoff) {
                $masukTerakhirPro = null; // sudah dipakai, jangan sampai match dobel
                continue; // tdk pop antrian, bukan bahan baku, bukan penjualan
            }

            $sisaKeluar = $qty;
            $biayaBarisIni = 0.0;
            $asalBarisIni = array(); // rincian lapisan yg dipakai utk baris keluar ini

            while ($sisaKeluar > 0.0000001) {
                if (empty($antrian[$item])) {
                    $sisaKeluar = 0; // stok kosong, tdk ada transaksi masuk sblmnya -> sisanya dianggap Rp 0
                    break;
                }
                $lapisan =& $antrian[$item][0];
                $ambil = min($lapisan['qty'], $sisaKeluar);
                $biayaBarisIni += $ambil * $lapisan['harga'];
                $asalBarisIni[] = array(
                    'qty' => $ambil, 'harga' => $lapisan['harga'],
                    'susumber' => $lapisan['susumber'], 'notransaksi' => $lapisan['notransaksi'], 'tanggal' => $lapisan['tanggal'],
                );
                $lapisan['qty'] -= $ambil;
                $sisaKeluar     -= $ambil;
                if ($lapisan['qty'] <= 0.0000001) array_shift($antrian[$item]);
                unset($lapisan);
            }

            $hargaSatuan = ($qty > 0) ? ($biayaBarisIni / $qty) : 0.0;

            if ($r->susumber === 'PRO') {
                // konsumsi bahan baku produksi -> masuk pool, bukan konsumsi bulanan --
                // TETAP dicatat sumber harga pokoknya (asalLog) utk Laporan Hasil Produksi.
                $biayaPool += $biayaBarisIni;
                $log[] = $this->_baris($r, 'KELUAR', $qty, $hargaSatuan, $biayaBarisIni, 0);
                $this->_simpanAsal($asalLog, $pt, $r->sdid, $asalBarisIni);
                continue;
            }

            $laporan = in_array($r->susumber, $this->susumberKeluarLaporan, true) ? 1 : 0;
            $log[] = $this->_baris($r, 'KELUAR', $qty, $hargaSatuan, $biayaBarisIni, $laporan);
            $this->_simpanAsal($asalLog, $pt, $r->sdid, $asalBarisIni);
        }

        return $log;
    }

    // Tambahkan rincian lapisan FIFO (asalBarisIni) ke $asalLog (by reference), utk disimpan
    // ke fhpprii_asal -- dipakai baik utk baris keluar sales (SJ/PY/PL) maupun bahan baku PRO.
    private function _simpanAsal(&$asalLog, $pt, $sdid, $asalBarisIni)
    {
        foreach ($asalBarisIni as $urutan => $a) {
            $asalLog[] = array(
                'HAPT'          => $pt,
                'HASDID'        => $sdid,
                'HAURUTAN'      => $urutan + 1,
                'HASUSUMBER'    => $a['susumber'],
                'HANOTRANSAKSI' => $a['notransaksi'],
                'HATANGGAL'     => $a['tanggal'],
                'HAQTY'         => $a['qty'],
                'HAHARGA'       => $a['harga'],
            );
        }
    }

    private function _baris($r, $tipe, $qty, $harga, $total, $laporan)
    {
        $pt = $this->getPt();
        return array(
            'HPPT'          => $pt,
            'HPSDID'        => $r->sdid,
            'HPSUID'        => $r->suid,
            'HPITEM'        => $r->sditem,
            'HPIKODE'       => $r->ikode,
            'HPINAMA'       => $r->inama,
            'HPTANGGAL'     => $r->sutanggal,
            'HPNOTRANSAKSI' => $r->sunotransaksi,
            'HPSUSUMBER'    => $r->susumber,
            'HPTIPE'        => $tipe,
            'HPQTY'         => $qty,
            'HPHARGA'       => $harga,
            'HPTOTAL'       => $total,
            'HPLAPORAN'     => $laporan,
            'HPBULAN'       => (int) date('n', strtotime($r->sutanggal)),
            'HPTAHUN'       => (int) date('Y', strtotime($r->sutanggal)),
        );
    }

    // Laporan bulanan (dipakai halaman interaktif): rekap per item utk satu bulan kalender.
    function laporanBulan($bulan, $tahun)
    {
        $tglDari = sprintf('%04d-%02d-01', $tahun, $bulan);
        $tglSampai = date('Y-m-t', strtotime($tglDari));
        return $this->laporanPeriode($tglDari, $tglSampai);
    }

    // Rincian baris transaksi (yg HPLAPORAN=1) utk satu item pada bulan terpilih -- utk drill-down.
    function detailItemBulan($item, $bulan, $tahun)
    {
        $tglDari = sprintf('%04d-%02d-01', $tahun, $bulan);
        $tglSampai = date('Y-m-t', strtotime($tglDari));
        return $this->detailItemPeriode($item, $tglDari, $tglSampai);
    }

    // Laporan periode bebas (dari-sampai tanggal, bisa lintas bulan) -- dipakai laporan cetak/excel.
    function laporanPeriode($tglDari, $tglSampai)
    {
        $pt = $this->getPt();

        $query = "SELECT HPITEM 'sditem', HPIKODE 'ikode', HPINAMA 'inama',
                         SUM(HPQTY) 'qtykeluar', SUM(HPTOTAL) 'totalhpp'
                    FROM fhpprii
                   WHERE HPPT = '".$this->db->escape_str($pt)."'
                     AND HPTANGGAL BETWEEN '".$this->db->escape_str($tglDari)."' AND '".$this->db->escape_str($tglSampai)."'
                     AND HPLAPORAN = 1
                   GROUP BY HPITEM, HPIKODE, HPINAMA
                   ORDER BY HPIKODE";

        $rows = $this->db->query($query)->result();
        $hasil = array();
        foreach ($rows as $r) {
            $hasil[] = array(
                'ikode'     => $r->ikode,
                'inama'     => $r->inama,
                'qtyterjual'=> (float) $r->qtykeluar,
                'totalhpp'  => (float) $r->totalhpp,
                'hpprata2'  => ($r->qtykeluar > 0) ? ((float) $r->totalhpp / (float) $r->qtykeluar) : 0,
            );
        }
        return $hasil;
    }

    // Rincian baris transaksi (yg HPLAPORAN=1) utk satu item pada periode bebas -- dipakai laporan cetak/excel.
    function detailItemPeriode($item, $tglDari, $tglSampai)
    {
        $pt = $this->getPt();

        $query = "SELECT HPSDID 'sdid', HPTANGGAL 'tanggal', HPNOTRANSAKSI 'notransaksi', HPSUSUMBER 'susumber',
                         HPQTY 'qty', HPHARGA 'harga', HPTOTAL 'total'
                    FROM fhpprii
                   WHERE HPPT = '".$this->db->escape_str($pt)."'
                     AND HPIKODE = '".$this->db->escape_str($item)."'
                     AND HPTANGGAL BETWEEN '".$this->db->escape_str($tglDari)."' AND '".$this->db->escape_str($tglSampai)."'
                     AND HPLAPORAN = 1
                   ORDER BY HPTANGGAL, HPID";

        $rows = $this->db->query($query)->result();
        $hasil = array();
        foreach ($rows as $r) {
            $hasil[] = array(
                'sdid'        => $r->sdid,
                'notransaksi' => $r->notransaksi,
                'tanggal'     => $r->tanggal,
                'susumber'    => $r->susumber,
                'qty'         => (float) $r->qty,
                'hppsatuan'   => (float) $r->harga,
                'hpp'         => (float) $r->total,
            );
        }
        return $hasil;
    }

    // Rincian lapisan FIFO yg dipakai utk membentuk HPP satu baris keluar (SDID) --
    // "sumber HPP"-nya berasal dari transaksi masuk yg mana saja.
    function asalTransaksi($sdid)
    {
        $query = "SELECT HASUSUMBER 'susumber', HANOTRANSAKSI 'notransaksi', HATANGGAL 'tanggal',
                         HAQTY 'qty', HAHARGA 'harga'
                    FROM fhpprii_asal
                   WHERE HASDID = ".(int) $sdid."
                ORDER BY HAURUTAN";

        $rows = $this->db->query($query)->result();
        $hasil = array();
        foreach ($rows as $r) {
            $hasil[] = array(
                'susumber'    => $r->susumber,
                'notransaksi' => $r->notransaksi,
                'tanggal'     => $r->tanggal,
                'qty'         => (float) $r->qty,
                'harga'       => (float) $r->harga,
            );
        }
        return $hasil;
    }

    // Laporan hasil produksi: apa yg DIHASILKAN tiap transaksi PRO (bukan apa yg terjual),
    // dikelompokkan per No Transaksi PRO. Baris bahan baku (KELUAR) & barang jadi (MASUK)
    // ditampilkan SATU LIST berurutan sesuai SDURUTAN asli (lewat HPID, yg mengikuti urutan
    // proses krn baris disimpan persis sesuai urutan SDURUTAN) -- supaya kalau satu transaksi
    // PRO berisi >1 batch (bahan baku A,B,C -> jadi 1, lalu bahan baku A,B,C -> jadi 2 lg),
    // urutannya di laporan tetap sesuai kejadian aslinya, bukan bahan baku digabung semua dulu.
    function laporanProduksi($tglDari, $tglSampai)
    {
        $pt = $this->getPt();

        $query = "SELECT HPSDID 'sdid', HPNOTRANSAKSI 'notransaksi', HPTANGGAL 'tanggal', HPTIPE 'tipe',
                         HPIKODE 'ikode', HPINAMA 'inama', HPQTY 'qty', HPHARGA 'harga', HPTOTAL 'total'
                    FROM fhpprii
                   WHERE HPPT = '".$this->db->escape_str($pt)."'
                     AND HPSUSUMBER = 'PRO'
                     AND HPTANGGAL BETWEEN '".$this->db->escape_str($tglDari)."' AND '".$this->db->escape_str($tglSampai)."'
                   ORDER BY HPTANGGAL, HPNOTRANSAKSI, HPID";

        $hasil = array();
        foreach ($this->db->query($query)->result() as $r) {
            $notransaksi = $r->notransaksi;
            if (!isset($hasil[$notransaksi])) {
                $hasil[$notransaksi] = array(
                    'notransaksi' => $notransaksi,
                    'tanggal'     => $r->tanggal,
                    'langkah'     => array(),
                );
            }
            $hasil[$notransaksi]['langkah'][] = array(
                'tipe'      => $r->tipe, // MASUK (barang jadi) / KELUAR (bahan baku)
                'sdid'      => $r->sdid,
                'ikode'     => $r->ikode,
                'inama'     => $r->inama,
                'qty'       => (float) $r->qty,
                'hppsatuan' => (float) $r->harga,
                'total'     => (float) $r->total,
            );
        }

        return array_values($hasil);
    }

    // Info kapan terakhir kali data PT RII diproses, dan sampai tanggal berapa.
    function infoProsesTerakhir()
    {
        $pt = $this->getPt();
        $row = $this->db->query("SELECT MAX(HPDIPROSES) 'terakhir', MAX(HPTANGGAL) 'sampai', COUNT(*) 'jumlah'
                                    FROM fhpprii WHERE HPPT='".$this->db->escape_str($pt)."'")->row();
        return $row;
    }

    // Bandingkan baris transaksi yg SEHARUSNYA ikut (kalau diproses ulang skrg, dgn filter
    // tglAwal/tglAkhir yg sama) dgn yg SUDAH tersimpan di fhpprii -- utk kasih tanda ke user
    // ada transaksi baru/berubah/dibatalkan yg blm ikut diproses.
    function cekStatus($tglAkhir, $tglAwal = null)
    {
        $pt = $this->getPt();
        if (!$pt) {
            return array('baru' => 0, 'usang' => 0);
        }

        // Pakai mesin FIFO yg SAMA PERSIS dgn prosesUlang() (bukan query WHERE terpisah),
        // supaya baris hand-off (yg memang sengaja TIDAK menghasilkan baris log) tdk salah
        // dianggap "belum diproses". Ini jg otomatis mendeteksi transaksi yg qty/harganya
        // BERUBAH (bukan cuma yg baru), krn nilainya ikut dibandingkan, tdk cuma SDID-nya.
        $log = $this->_hitungLog($pt, $tglAkhir, $tglAwal);
        $seharusnya = array(); // sdid => tanda (qty|harga) hasil hitung skrg
        foreach ($log as $baris) {
            $seharusnya[(int) $baris['HPSDID']] = round((float) $baris['HPQTY'], 4).'|'.round((float) $baris['HPHARGA'], 4);
        }

        $sudah = array(); // sdid => tanda (qty|harga) yg tersimpan di fhpprii
        foreach ($this->db->query("SELECT HPSDID 'sdid', HPQTY 'qty', HPHARGA 'harga' FROM fhpprii WHERE HPPT='".$this->db->escape_str($pt)."'")->result() as $r) {
            $sudah[(int) $r->sdid] = round((float) $r->qty, 4).'|'.round((float) $r->harga, 4);
        }

        $baru = 0;
        foreach ($seharusnya as $sdid => $tanda) {
            if (!isset($sudah[$sdid]) || $sudah[$sdid] !== $tanda) $baru++;
        }

        $usang = 0;
        foreach ($sudah as $sdid => $x) {
            if (!isset($seharusnya[$sdid])) $usang++;
        }

        return array('baru' => $baru, 'usang' => $usang);
    }

}
