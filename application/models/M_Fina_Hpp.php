<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Perhitungan HPP (Harga Pokok Penjualan) metode FIFO.
 *
 * Antrian FIFO dihitung PER GUDANG (fstokd.SDGUDANG), dari SELURUH histori
 * mutasi stok (bukan cuma bulan yg dipilih) sampai akhir bulan yg dipilih --
 * supaya urutan lapisan stoknya benar. Yang dilaporkan sbg HPP hanya baris
 * keluar yg (a) tanggalnya jatuh di bulan yg dipilih, dan (b) sumbernya
 * termasuk "penjualan" (lihat $susumberPenjualan).
 *
 * Semua jenis stok masuk (pembelian, mutasi masuk, penyesuaian, dst) ikut
 * membentuk antrian. Kalau harga satuan baris masuk itu 0/kosong (belum
 * ter-invoice dsb) atau antrian stoknya kosong saat ada baris keluar,
 * HPP-nya dianggap Rp 0 -- TIDAK pakai bitem.ICOGS sbg cadangan.
 *
 * --- Transaksi PRODUKSI (SUSUMBER='PRO') ---
 * Satu transaksi PRO bisa berisi >1 batch produksi tercampur, urut SDURUTAN:
 *   [baris keluar bahan baku/kemasan] -> [baris MASUK barang jadi]
 *   -> [baris KELUAR barang jadi yg SAMA & qty SAMA -- hand-off, bukan penjualan]
 * HPP satuan barang jadi = total HPP FIFO bahan baku sejak batch sebelumnya
 * (atau awal transaksi) ditutup, dibagi qty masuknya. Baris hand-off memakai
 * harga itu juga (konsumsi lapisan yg baru saja didorong, net 0 di gudang PRO).
 *
 * Transaksi TMB (SUSUMBER='TMB') dgn SUPRUID terisi = "menarik" hasil produksi
 * itu ke gudang tujuan sebenarnya; HPP satuan baris masuknya diambil dari hasil
 * hitungan PRO yg ditunjuk SUPRUID (dicocokkan per item), bukan SDHARGA/ICOGS.
 *
 * Karena itu, sebelum menghitung ledger gudang yg dipilih user, gudang manapun
 * dlm PT yg sama yg punya transaksi PRO diproses dulu (lihat hitungFifo()).
 */
class M_Fina_Hpp extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    // Kode SUSUMBER yg dianggap "penjualan" (butuh HPP): POS Tunai, Alkes/Editdepo,
    // Surat Jalan & Faktur Penjualan (B2B), Retur Penjualan (kalau tercatat sbg keluar).
    private $susumberPenjualan = array('IP', 'AL', 'SJ', 'IV', 'SR');

    function hitungFifo($pt, $cabang, $tglAwal, $tglAkhir)
    {
        $gudangProduksi = $this->_cariGudangProduksi($pt, $tglAkhir);

        $biayaProduksi = array(); // [suid_pro][item] => harga satuan hasil produksi
        $sudahDiproses = array(); // gid => hasil (ringkasan penjualan gudang itu)

        foreach ($gudangProduksi as $gid) {
            $r = $this->_jalankanLedger($gid, $tglAwal, $tglAkhir, $biayaProduksi);
            $sudahDiproses[$gid] = $r;
        }

        if (isset($sudahDiproses[$cabang])) {
            return $sudahDiproses[$cabang];
        }

        return $this->_jalankanLedger($cabang, $tglAwal, $tglAkhir, $biayaProduksi);
    }

    // Gudang mana saja (di PT ini) yg punya transaksi PRO s.d. tglAkhir -- ini harus
    // diproses lebih dulu supaya $biayaProduksi siap sblm gudang tujuan (mis. via TMB)
    // membutuhkannya.
    private function _cariGudangProduksi($pt, $tglAkhir)
    {
        $query = "SELECT DISTINCT D.sdgudang 'gid'
                    FROM fstoku H
              INNER JOIN fstokd D ON D.sdidsu = H.suid
              INNER JOIN bgudang G ON G.gid = D.sdgudang
                   WHERE H.susumber = 'PRO' AND H.sustatus <> 9
                     AND G.gpt = '".$this->db->escape_str($pt)."'
                     AND H.sutanggal <= '".$tglAkhir."'";

        $rows = $this->db->query($query)->result();
        $out = array();
        foreach ($rows as $r) { $out[] = $r->gid; }
        return $out;
    }

    // Jalankan simulasi FIFO utk satu gudang, urut TRANSAKSI ASLI (bukan per item dulu)
    // supaya baris2 dlm satu transaksi PRO tetap berdekatan sesuai SDURUTAN-nya.
    // $biayaProduksi diisi/dipakai lintas panggilan (passed by reference).
    private function _jalankanLedger($gudang, $tglAwal, $tglAkhir, &$biayaProduksi)
    {
        $query = "SELECT H.suid 'suid', H.sunotransaksi 'sunotransaksi', H.susumber 'susumber',
                         H.supruid 'supruid', H.sutanggal 'sutanggal', D.sdurutan 'sdurutan',
                         D.sditem 'sditem', I.ikode 'ikode', I.inama 'inama', IFNULL(I.iqtyperbox,1) 'iqtyperbox',
                         IFNULL(D.sdmasuk,0) 'sdmasuk', IFNULL(D.sdkeluar,0) 'sdkeluar', IFNULL(D.sdharga,0) 'sdharga'
                    FROM fstokd D
              INNER JOIN fstoku H ON H.suid = D.sdidsu
              INNER JOIN bitem  I ON I.iid  = D.sditem
                   WHERE H.sustatus <> 9
                     AND D.sdgudang = '".$this->db->escape_str($gudang)."'
                     AND H.sutanggal <= '".$tglAkhir."'
                     AND (D.sdmasuk > 0 OR D.sdkeluar > 0)
                ORDER BY H.sutanggal, H.suid, D.sdurutan";

        $rows = $this->db->query($query)->result();

        $antrian = array(); // sditem => array of ['qty'=>,'harga'=>,'susumber'=>,'notransaksi'=>,'tanggal'=>,'pronotransaksi'=>], lama->baru
        $hasil   = array(); // sditem => ringkasan penjualan bulan terpilih
        $proInfo = array(); // suid transaksi PRO => ['notransaksi'=>, 'tanggal'=>], utk penelusuran "No PRO"

        $suidProAktif     = null;
        $biayaPool        = 0.0;
        $masukTerakhirPro = null; // ['item'=>, 'qty'=>] baris masuk PRO paling akhir dlm transaksi ini

        foreach ($rows as $r) {
            $item = $r->sditem;
            if (!isset($antrian[$item])) $antrian[$item] = array();

            if ($r->susumber === 'PRO') {
                $proInfo[$r->suid] = array('notransaksi' => $r->sunotransaksi, 'tanggal' => $r->sutanggal);
            }

            if ($r->susumber === 'PRO' && $r->suid !== $suidProAktif) {
                // pindah ke transaksi PRO baru -> reset akumulator biaya
                $suidProAktif     = $r->suid;
                $biayaPool        = 0.0;
                $masukTerakhirPro = null;
            }

            if ($r->sdmasuk > 0) {
                $pronotransaksi = null; // No PRO asal, cuma keisi kalau baris ini TMB penarikan produksi
                $qtyMasuk = (float) $r->sdmasuk;

                if ($r->susumber === 'TMB' && (float) $r->iqtyperbox > 1) {
                    // Qty TMB dicatat dlm satuan box -- konversi ke satuan eceran spy nyambung
                    // dgn qty penjualan (SJ/POS dst yg selalu dlm eceran).
                    $qtyMasuk = $qtyMasuk * (float) $r->iqtyperbox;
                }

                if ($r->susumber === 'PRO') {
                    $harga = ($biayaPool > 0) ? ($biayaPool / (float) $r->sdmasuk) : 0.0;
                    // Satu SUID PRO bisa punya baris masuk item yg sama di gudang lain (mis. "Sample
                    // Bahan Jadi") tanpa bahan baku terlihat di ledger gudang itu sendiri -- JANGAN
                    // sampai baris tanpa dasar biaya (biayaPool=0) menimpa hasil yg sudah benar dari
                    // baris produksi utamanya. Baris berdasar biaya nyata (biayaPool>0) selalu menang.
                    if ($biayaPool > 0 || !isset($biayaProduksi[$r->suid][$item])) {
                        $biayaProduksi[$r->suid][$item] = $harga;
                    }
                    $masukTerakhirPro = array('item' => $item, 'qty' => (float) $r->sdmasuk);
                    $biayaPool = 0.0;
                } elseif ($r->susumber === 'TMB' && !empty($r->supruid) && isset($biayaProduksi[$r->supruid][$item])) {
                    $harga = $biayaProduksi[$r->supruid][$item];
                    $pronotransaksi = isset($proInfo[$r->supruid]) ? $proInfo[$r->supruid]['notransaksi'] : null;
                } else {
                    $harga = ($r->sdharga > 0) ? (float) $r->sdharga : 0.0;
                }
                $antrian[$item][] = array(
                    'qty'            => $qtyMasuk,
                    'harga'          => $harga,
                    'susumber'       => $r->susumber,
                    'notransaksi'    => $r->sunotransaksi,
                    'tanggal'        => $r->sutanggal,
                    'pronotransaksi' => $pronotransaksi,
                );
                continue;
            }

            if ($r->sdkeluar <= 0) continue;

            // hand-off: baris keluar PRO utk item & qty SAMA persis dgn masuk barusan di transaksi ini
            $handoff = ($r->susumber === 'PRO' && $masukTerakhirPro !== null
                        && $masukTerakhirPro['item'] === $item
                        && abs($masukTerakhirPro['qty'] - (float) $r->sdkeluar) < 0.0000001);

            $sisaKeluar    = (float) $r->sdkeluar;
            $biayaBarisIni = 0.0;
            $asalBarisIni  = array(); // rincian lapisan FIFO yg dipakai utk baris keluar ini

            while ($sisaKeluar > 0.0000001) {
                if (empty($antrian[$item])) {
                    // stok habis di antrian, tp masih ada baris keluar -> HPP dianggap Rp 0 (bukan ICOGS)
                    $asalBarisIni[] = array(
                        'qty' => $sisaKeluar, 'harga' => 0.0,
                        'susumber' => 'KOSONG', 'notransaksi' => null, 'tanggal' => null, 'pronotransaksi' => null,
                    );
                    $sisaKeluar = 0;
                    break;
                }
                $lapisan =& $antrian[$item][0];
                $ambil = min($lapisan['qty'], $sisaKeluar);
                $biayaBarisIni += $ambil * $lapisan['harga'];
                $asalBarisIni[] = array(
                    'qty' => $ambil, 'harga' => $lapisan['harga'],
                    'susumber' => $lapisan['susumber'], 'notransaksi' => $lapisan['notransaksi'],
                    'tanggal' => $lapisan['tanggal'], 'pronotransaksi' => $lapisan['pronotransaksi'],
                );
                $lapisan['qty'] -= $ambil;
                $sisaKeluar     -= $ambil;
                if ($lapisan['qty'] <= 0.0000001) array_shift($antrian[$item]);
                unset($lapisan);
            }

            if ($handoff) {
                $masukTerakhirPro = null; // sudah dipakai, jangan sampai match dobel
                continue; // bukan bahan baku, bukan penjualan -> lewati
            }

            if ($r->susumber === 'PRO') {
                // bahan baku/kemasan produksi -> masuk pool biaya, bukan penjualan
                $biayaPool += $biayaBarisIni;
                continue;
            }

            $masukPeriode    = ($r->sutanggal >= $tglAwal && $r->sutanggal <= $tglAkhir);
            $adalahPenjualan = in_array($r->susumber, $this->susumberPenjualan, true);

            if ($masukPeriode && $adalahPenjualan) {
                if (!isset($hasil[$item])) {
                    $hasil[$item] = array('ikode' => $r->ikode, 'inama' => $r->inama, 'qtyterjual' => 0.0, 'totalhpp' => 0.0, 'detail' => array());
                }
                $hasil[$item]['qtyterjual'] += (float) $r->sdkeluar;
                $hasil[$item]['totalhpp']   += $biayaBarisIni;
                // Rincian sumber: tiap baris transaksi penjualan yg menyumbang ke total di atas,
                // supaya bisa ditelusuri dari mana nilai HPP-nya berasal.
                $hasil[$item]['detail'][] = array(
                    'notransaksi' => $r->sunotransaksi,
                    'tanggal'     => $r->sutanggal,
                    'susumber'    => $r->susumber,
                    'qty'         => (float) $r->sdkeluar,
                    'hpp'         => $biayaBarisIni,
                    'hppsatuan'   => ((float) $r->sdkeluar > 0) ? ($biayaBarisIni / (float) $r->sdkeluar) : 0,
                    'asal'        => $asalBarisIni,
                );
            }
        }

        foreach ($hasil as &$h) {
            $h['hpprata2'] = ($h['qtyterjual'] > 0) ? ($h['totalhpp'] / $h['qtyterjual']) : 0;
        }
        unset($h);

        return $hasil;
    }

}
