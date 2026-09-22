<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Dias_Ai extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        if (!$this->session->has_userdata('nama')) {
            redirect(base_url('exception'));
        }
        $this->load->model('M_Dias_Ai');
        $this->load->library('Dias_Ai_Api');
    }

    function chat()
    {
        header('Content-Type: application/json');

        $pesanUser = trim((string) $this->input->post('pesan'));
        $riwayat   = trim((string) $this->input->post('riwayat'));

        if ($pesanUser === '') {
            echo json_encode(array('pesan' => 'error', 'error' => 'Pesan tidak boleh kosong.'));
            return;
        }

        try {
            $katalogLaporan = $this->M_Dias_Ai->getKatalogLaporan();

            if (empty($katalogLaporan)) {
                echo json_encode(array(
                    'pesan' => 'sukses', 'ditemukan' => false,
                    'balasan' => 'Maaf, Anda belum punya akses ke laporan manapun, jadi saya belum bisa membantu mengambilkan data.',
                ));
                return;
            }

            $katalogPt     = $this->M_Dias_Ai->getKatalogPt();
            $katalogGudang = $this->M_Dias_Ai->getKatalogGudang();

            $systemPrompt = $this->_buatSystemPrompt($katalogLaporan, $katalogPt, $katalogGudang);

            $isiPesan = $pesanUser;
            if ($riwayat !== '') {
                $isiPesan = "Riwayat percakapan sebelumnya (utk konteks):\n" . $riwayat . "\n\nPesan terbaru dari user:\n" . $pesanUser;
            }

            $teksMentah = $this->dias_ai_api->kirim($systemPrompt, $isiPesan);
            $hasil = $this->_uraiJson($teksMentah);

            if ($hasil === null) {
                echo json_encode(array(
                    'pesan' => 'sukses', 'ditemukan' => false,
                    'balasan' => 'Maaf, saya belum paham permintaannya. Bisa dijelaskan lagi laporan apa dan periode tanggal berapa yang Anda maksud?',
                ));
                return;
            }

            $ditemukan = !empty($hasil['ditemukan']) && !empty($hasil['arid']);
            $balasan   = isset($hasil['pesan']) ? (string) $hasil['pesan'] : '';

            if (!$ditemukan) {
                echo json_encode(array(
                    'pesan' => 'sukses', 'ditemukan' => false,
                    'balasan' => $balasan !== '' ? $balasan : 'Maaf, saya belum menemukan laporan yang cocok. Bisa dijelaskan lagi?',
                ));
                return;
            }

            // Validasi ulang server-side -- JANGAN percaya arid dari AI begitu saja,
            // pastikan tetap dari katalog yg user ybs benar2 berhak akses.
            $laporan = $this->M_Dias_Ai->bolehAksesLaporan($hasil['arid']);
            if (!$laporan) {
                echo json_encode(array(
                    'pesan' => 'sukses', 'ditemukan' => false,
                    'balasan' => 'Maaf, laporan yang cocok sepertinya bukan yang bisa Anda akses. Coba jelaskan ulang permintaannya.',
                ));
                return;
            }

            echo json_encode(array(
                'pesan'     => 'sukses',
                'ditemukan' => true,
                'balasan'   => $balasan !== '' ? $balasan : ('Baik, saya siapkan "' . $laporan->nama . '".'),
                'laporan'   => array(
                    'arid' => (int) $laporan->arid,
                    'nama' => $laporan->nama,
                ),
                'filter' => array(
                    'tgldari'   => $this->_bersihkanTanggal($hasil, 'tgldari'),
                    'tglsampai' => $this->_bersihkanTanggal($hasil, 'tglsampai'),
                    'tgl'       => $this->_bersihkanTanggal($hasil, 'tgl'),
                    'namapt'    => (!empty($hasil['namapt']) ? (int) $hasil['namapt'] : ''),
                    'gudang'    => (!empty($hasil['gudang']) ? (int) $hasil['gudang'] : ''),
                    'saldo'     => 0,
                ),
            ));
        } catch (\Throwable $e) {
            echo json_encode(array('pesan' => 'error', 'error' => $e->getMessage()));
        }
    }

    private function _bersihkanTanggal($hasil, $field)
    {
        if (empty($hasil[$field])) return '';
        $v = trim((string) $hasil[$field]);
        if (!preg_match('/^\d{2}-\d{2}-\d{4}$/', $v)) return '';
        return $v;
    }

    private function _uraiJson($teks)
    {
        $teks = trim((string) $teks);
        $teks = preg_replace('/^```json\s*/i', '', $teks);
        $teks = preg_replace('/^```\s*/', '', $teks);
        $teks = preg_replace('/```\s*$/', '', $teks);
        $teks = trim($teks);

        $data = json_decode($teks, true);
        if (!is_array($data)) return null;
        return $data;
    }

    private function _buatSystemPrompt($katalogLaporan, $katalogPt, $katalogGudang)
    {
        $daftarLaporan = array();
        foreach ($katalogLaporan as $r) {
            $daftarLaporan[] = array(
                'arid'                      => (int) $r->arid,
                'nama'                      => $r->nama,
                'butuh_tanggal_dari_sampai' => ((int) $r->ardate1f === 1),
                'butuh_satu_tanggal'        => ((int) $r->ardate2f === 1),
                'ada_filter_cabang'         => ((int) $r->argudangf === 1),
                'ada_filter_pt'             => ((int) $r->arptf === 1),
            );
        }

        $daftarPt = array();
        foreach ($katalogPt as $p) {
            $daftarPt[] = array('id' => (int) $p->id, 'nama' => $p->nama);
        }

        $daftarGudang = array();
        foreach ($katalogGudang as $g) {
            $daftarGudang[] = array('id' => (int) $g->id, 'nama' => $g->nama, 'pt' => $g->pt);
        }

        $hariIni = date('d-m-Y');

        $prompt  = "Kamu adalah asisten \"DIAS AI\" di aplikasi POS/akuntansi klinik. TUGASMU HANYA SATU: ";
        $prompt .= "membaca permintaan data dari user (bahasa Indonesia natural), lalu mencocokkannya ke SATU ";
        $prompt .= "laporan dari daftar di bawah, dan mengisi parameter filternya. Kamu TIDAK BOLEH menyusun ";
        $prompt .= "query SQL, TIDAK BOLEH menjawab pertanyaan di luar topik laporan/data, dan TIDAK BOLEH ";
        $prompt .= "memilih laporan yang tidak ada di daftar ini.\n\n";
        $prompt .= "Tanggal hari ini: " . $hariIni . " (format DD-MM-YYYY). Gunakan ini utk menafsirkan kata ";
        $prompt .= "spt \"bulan ini\", \"bulan lalu\", \"minggu ini\", dst.\n\n";
        $prompt .= "Daftar laporan yang boleh dipilih (JSON):\n" . json_encode($daftarLaporan) . "\n\n";
        $prompt .= "Daftar PT (JSON, dipakai kalau laporan yg dipilih ada_filter_pt=true):\n" . json_encode($daftarPt) . "\n\n";
        $prompt .= "Daftar Cabang/Gudang (JSON, dipakai kalau laporan yg dipilih ada_filter_cabang=true; field ";
        $prompt .= "pt cocokkan ke id PT di atas):\n" . json_encode($daftarGudang) . "\n\n";
        $prompt .= "WAJIB balas HANYA dengan SATU objek JSON (tanpa teks lain, tanpa markdown), persis skema ini:\n";
        $prompt .= "{\n";
        $prompt .= "  \"ditemukan\": true/false,\n";
        $prompt .= "  \"arid\": <angka arid dari daftar laporan, atau null kalau tidak ditemukan/tidak yakin>,\n";
        $prompt .= "  \"tgldari\": \"DD-MM-YYYY\" atau null (isi HANYA kalau butuh_tanggal_dari_sampai=true),\n";
        $prompt .= "  \"tglsampai\": \"DD-MM-YYYY\" atau null (isi HANYA kalau butuh_tanggal_dari_sampai=true),\n";
        $prompt .= "  \"tgl\": \"DD-MM-YYYY\" atau null (isi HANYA kalau butuh_satu_tanggal=true),\n";
        $prompt .= "  \"namapt\": <id PT dari daftar PT, atau null>,\n";
        $prompt .= "  \"gudang\": <id gudang dari daftar cabang, atau null>,\n";
        $prompt .= "  \"pesan\": \"<1-2 kalimat bahasa Indonesia, jelaskan singkat ke user apa yg kamu pahami/";
        $prompt .= "siapkan, ATAU kalau ditemukan=false, jelaskan kenapa & tanyakan klarifikasi yg dibutuhkan>\"\n";
        $prompt .= "}\n\n";
        $prompt .= "Kalau permintaan user tidak jelas laporan mana yg dimaksud, atau tanggal/PT/cabangnya kurang ";
        $prompt .= "jelas, set ditemukan=false dan tanyakan klarifikasi lewat field \"pesan\" -- JANGAN menebak ";
        $prompt .= "arid sembarangan. Kalau laporan butuh tanggal tapi user tidak sebutkan periode sama sekali, ";
        $prompt .= "anggap maksudnya bulan berjalan (dari tanggal 1 bulan ini s.d. hari ini).";

        return $prompt;
    }

}
