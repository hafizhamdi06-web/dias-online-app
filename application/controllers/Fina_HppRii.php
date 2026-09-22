<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Fina_HppRii extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        if (!$this->session->has_userdata('nama')) {
            redirect(base_url('exception'));
        }
        $this->load->model('M_Fina_HppRii');
    }

    function proses()
    {
        header('Content-Type: application/json');

        $tglAkhir = trim((string) $this->input->post('tglakhir'));
        $tglAkhir = ($tglAkhir === '') ? date('Y-m-d') : tgl_database($tglAkhir);

        $tglAwal = trim((string) $this->input->post('tglawal'));
        $tglAwal = ($tglAwal === '') ? null : tgl_database($tglAwal);

        try {
            $jumlah = $this->M_Fina_HppRii->prosesUlang($tglAkhir, $tglAwal);
            echo json_encode(array('pesan' => 'sukses', 'jumlah' => $jumlah));
        } catch (\Throwable $e) {
            echo json_encode(array('pesan' => 'error', 'error' => $e->getMessage()));
        }
    }

    function laporan()
    {
        header('Content-Type: application/json');

        $bulan = (int) $this->input->post('bulan');
        $tahun = (int) $this->input->post('tahun');

        if ($bulan < 1 || $bulan > 12 || $tahun < 2000) {
            echo json_encode(array('pesan' => 'error', 'error' => 'Bulan dan Tahun wajib diisi.'));
            return;
        }

        try {
            $data = $this->M_Fina_HppRii->laporanBulan($bulan, $tahun);
            $totalQty = 0; $totalHpp = 0;
            foreach ($data as $d) { $totalQty += $d['qtyterjual']; $totalHpp += $d['totalhpp']; }
            echo json_encode(array('pesan' => 'sukses', 'data' => $data, 'total_qty' => $totalQty, 'total_hpp' => $totalHpp));
        } catch (\Throwable $e) {
            echo json_encode(array('pesan' => 'error', 'error' => $e->getMessage()));
        }
    }

    function detail()
    {
        header('Content-Type: application/json');

        $item  = trim((string) $this->input->post('item'));
        $bulan = (int) $this->input->post('bulan');
        $tahun = (int) $this->input->post('tahun');

        if ($item === '' || $bulan < 1 || $bulan > 12 || $tahun < 2000) {
            echo json_encode(array('pesan' => 'error', 'error' => 'Parameter tidak lengkap.'));
            return;
        }

        try {
            $data = $this->M_Fina_HppRii->detailItemBulan($item, $bulan, $tahun);
            echo json_encode(array('pesan' => 'sukses', 'data' => $data));
        } catch (\Throwable $e) {
            echo json_encode(array('pesan' => 'error', 'error' => $e->getMessage()));
        }
    }

    function info()
    {
        header('Content-Type: application/json');

        try {
            $row = $this->M_Fina_HppRii->infoProsesTerakhir();
            echo json_encode(array('pesan' => 'sukses', 'terakhir' => $row->terakhir, 'sampai' => $row->sampai, 'jumlah' => $row->jumlah));
        } catch (\Throwable $e) {
            echo json_encode(array('pesan' => 'error', 'error' => $e->getMessage()));
        }
    }

    // Bandingkan data live vs yg sudah tersimpan -- tanda ada transaksi baru/berubah/
    // dibatalkan yg belum ikut diproses.
    function cek()
    {
        header('Content-Type: application/json');

        $tglAkhir = trim((string) $this->input->post('tglakhir'));
        $tglAkhir = ($tglAkhir === '') ? date('Y-m-d') : tgl_database($tglAkhir);

        $tglAwal = trim((string) $this->input->post('tglawal'));
        $tglAwal = ($tglAwal === '') ? null : tgl_database($tglAwal);

        try {
            $status = $this->M_Fina_HppRii->cekStatus($tglAkhir, $tglAwal);
            echo json_encode(array('pesan' => 'sukses', 'baru' => $status['baru'], 'usang' => $status['usang']));
        } catch (\Throwable $e) {
            echo json_encode(array('pesan' => 'error', 'error' => $e->getMessage()));
        }
    }

    // Rincian lapisan FIFO (sumber HPP) yg dipakai utk satu baris keluar (SDID).
    function asal()
    {
        header('Content-Type: application/json');

        $sdid = (int) $this->input->post('sdid');

        try {
            $data = $this->M_Fina_HppRii->asalTransaksi($sdid);
            echo json_encode(array('pesan' => 'sukses', 'data' => $data));
        } catch (\Throwable $e) {
            echo json_encode(array('pesan' => 'error', 'error' => $e->getMessage()));
        }
    }

}
