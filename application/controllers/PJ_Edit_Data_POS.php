<?php defined('BASEPATH') OR exit('No direct script access allowed');

class PJ_Edit_Data_POS extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        if(!$this->session->has_userdata('nama')){
            redirect(base_url('exception'));
        }
        $this->load->model('M_transaksi');
        $this->load->model('M_PJ_Edit_Data_POS');
    }

    private function _cabangValid($cabang){
        if (!empty($this->session->allcabang) && $this->session->allcabang == 1) {
            return (int) $cabang;
        }

        $ucabangpilih = '';
        $sql = "SELECT UCABANGPILIH FROM auser WHERE UID='".$this->session->id."'";
        $res = $this->db->query($sql);
        foreach ($res->result() as $row) {
            $ucabangpilih = $row->UCABANGPILIH;
        }
        $allowed = array_filter(array_map('trim', explode(',', $ucabangpilih)));
        if (!in_array($cabang, $allowed)) {
            return 0;
        }
        return (int) $cabang;
    }

    // Daftar baris item POS yang pembayarannya lewat merchant (sumerchantjumlah)
    function getdata(){
        $tgldari   = tgl_database($this->input->post('tgldari'));
        $tglsampai = tgl_database($this->input->post('tglsampai'));
        $cabang    = $this->_cabangValid($this->input->post('cabang'));

        $query = "SELECT D.sdid 'sdid',
                         H.suid 'suid',
                         H.sunotransaksi 'notransaksi',
                         DATE_FORMAT(H.sutanggal,'%d-%m-%Y') 'tanggal',
                         COALESCE(K.knama,'-') 'pasien',
                         COALESCE(I.ikode,'-') 'kodeitem',
                         COALESCE(I.inama,'-') 'namaitem',
                         D.sdkeluar 'qty',
                         D.sdharga 'harga',
                         D.sddiskonpersen 'diskonpersen',
                         D.sddiskon 'diskon',
                         (D.sdkeluar * D.sdharga - D.sddiskon) 'subtotal',
                         H.sumerchantjumlah 'merchantjumlah'
                    FROM fstokd D
              INNER JOIN fstoku H ON D.sdidsu = H.suid
               LEFT JOIN bkontak K ON H.sukontak = K.kid
               LEFT JOIN bitem I ON D.sditem = I.iid
                   WHERE H.susumber = 'IP' AND H.sustatus <> 9
                     AND H.sutanggal BETWEEN '".$tgldari."' AND '".$tglsampai."'
                     AND H.sucabang = '".$cabang."'
                     AND COALESCE(H.sumerchantjumlah,0) > 0
                ORDER BY H.sunotransaksi ASC, D.sdurutan ASC";

        header('Content-Type: application/json');
        echo $this->M_transaksi->get_data_query($query);
    }

    // Simpan cepat 1 baris: sddiskonpersen + sddiskon, lalu hitung ulang sumerchantjumlah transaksi
    function savedata(){
        echo $this->M_PJ_Edit_Data_POS->simpanBaris();
    }

}
