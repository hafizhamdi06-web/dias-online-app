<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Fina_Hpp extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        if (!$this->session->has_userdata('nama')) {
            redirect(base_url('exception'));
        }
        $this->load->model('M_Fina_Hpp');
    }

    function hitung()
    {
        header('Content-Type: application/json');

        $bulan  = (int) $this->input->post('bulan');
        $tahun  = (int) $this->input->post('tahun');
        $cabang = trim((string) $this->input->post('cabang'));
        $pt     = trim((string) $this->input->post('pt'));

        if ($bulan < 1 || $bulan > 12 || $tahun < 2000 || $cabang === '' || $pt === '') {
            echo json_encode(array('pesan' => 'error', 'error' => 'PT, Bulan, Tahun, dan Cabang wajib diisi.'));
            return;
        }

        $tglAwal  = sprintf('%04d-%02d-01', $tahun, $bulan);
        $tglAkhir = date('Y-m-t', strtotime($tglAwal));

        try {
            $hasil = $this->M_Fina_Hpp->hitungFifo($pt, $cabang, $tglAwal, $tglAkhir);
            $data = array_values($hasil);
            usort($data, function ($a, $b) { return strcmp($a['ikode'], $b['ikode']); });

            $totalQty = 0; $totalHpp = 0;
            foreach ($data as $d) { $totalQty += $d['qtyterjual']; $totalHpp += $d['totalhpp']; }

            echo json_encode(array(
                'pesan' => 'sukses',
                'periode' => array('tgldari' => $tglAwal, 'tglsampai' => $tglAkhir),
                'data' => $data,
                'total_qty' => $totalQty,
                'total_hpp' => $totalHpp,
            ));
        } catch (\Throwable $e) {
            echo json_encode(array('pesan' => 'error', 'error' => $e->getMessage()));
        }
    }

}
