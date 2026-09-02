<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Grafik_Penjualan extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        if(!$this->session->has_userdata('nama')){
            redirect(base_url('exception'));
        }
        $this->load->model('M_transaksi');
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

    function getdata(){
        $tgldari = tgl_database($_POST['tgldari']);
        $tglsampai = tgl_database($_POST['tglsampai']);
        $cabang = $this->_cabangValid($_POST['cabang']);

        $query = "SELECT DATE_FORMAT(A.sutanggal,'%Y-%m') 'bulan',
                          SUM(A.sutotaltransaksi) 'omzet',
                          COUNT(DISTINCT A.suid) 'jumlahtransaksi',
                          COUNT(DISTINCT A.sukontak) 'jumlahpasien'
                     FROM fstoku A
                    WHERE A.susumber='IP' AND A.sustatus<>9
                      AND A.sutanggal BETWEEN '".$tgldari."' AND '".$tglsampai."'
                      AND A.sucabang = '".$cabang."'
                 GROUP BY DATE_FORMAT(A.sutanggal,'%Y-%m')
                 ORDER BY bulan ASC";

        header('Content-Type: application/json');
        echo $this->M_transaksi->get_data_query($query);
    }

    function toppasien(){
        $tgldari = tgl_database($_POST['tgldari']);
        $tglsampai = tgl_database($_POST['tglsampai']);
        $cabang = $this->_cabangValid($_POST['cabang']);

        $query = "SELECT K.knama 'nama',
                         K.kidpasien 'idpasien',
                         K.k1telp1 'nohp',
                         SUM(A.sutotaltransaksi) 'total'
                    FROM fstoku A
               LEFT JOIN bkontak K ON A.sukontak = K.kid
                   WHERE A.susumber='IP' AND A.sustatus<>9
                     AND A.sutanggal BETWEEN '".$tgldari."' AND '".$tglsampai."'
                     AND A.sucabang = '".$cabang."'
                GROUP BY A.sukontak
                ORDER BY total DESC
                   LIMIT 10";

        header('Content-Type: application/json');
        echo $this->M_transaksi->get_data_query($query);
    }

    function topproduk(){
        $tgldari = tgl_database($_POST['tgldari']);
        $tglsampai = tgl_database($_POST['tglsampai']);
        $cabang = $this->_cabangValid($_POST['cabang']);

        $query = "SELECT I.ikode 'kode',
                         I.inama 'nama',
                         SUM(D.sdkeluar) 'qty',
                         SUM(D.sdkeluar * D.sdharga - D.sddiskon) 'total'
                    FROM fstokd D
              INNER JOIN fstoku H ON D.sdidsu = H.suid
              INNER JOIN bitem I ON D.sditem = I.iid
                   WHERE H.susumber='IP' AND H.sustatus<>9
                     AND H.sutanggal BETWEEN '".$tgldari."' AND '".$tglsampai."'
                     AND H.sucabang = '".$cabang."'
                GROUP BY D.sditem
                ORDER BY qty DESC, total DESC
                   LIMIT 10";

        header('Content-Type: application/json');
        echo $this->M_transaksi->get_data_query($query);
    }

    function topprodukqty(){
        $tgldari = tgl_database($_POST['tgldari']);
        $tglsampai = tgl_database($_POST['tglsampai']);
        $cabang = $this->_cabangValid($_POST['cabang']);

        $query = "SELECT I.ikode 'kode',
                         I.inama 'nama',
                         SUM(D.sdkeluar) 'qty',
                         SUM(D.sdkeluar * D.sdharga - D.sddiskon) 'total'
                    FROM fstokd D
              INNER JOIN fstoku H ON D.sdidsu = H.suid
              INNER JOIN bitem I ON D.sditem = I.iid
                   WHERE H.susumber='IP' AND H.sustatus<>9
                     AND (D.sdkeluar * D.sdharga - D.sddiskon) > 0
                     AND H.sutanggal BETWEEN '".$tgldari."' AND '".$tglsampai."'
                     AND H.sucabang = '".$cabang."'
                GROUP BY D.sditem
                ORDER BY qty DESC, total DESC
                   LIMIT 10";

        header('Content-Type: application/json');
        echo $this->M_transaksi->get_data_query($query);
    }

    function ringkasan(){
        $cabang = $this->_cabangValid($_POST['cabang']);

        $query = "SELECT
                     SUM(CASE WHEN A.sutanggal = CURDATE() THEN A.sutotaltransaksi ELSE 0 END) 'omzethariini',
                     COUNT(DISTINCT CASE WHEN A.sutanggal = CURDATE() THEN A.sukontak END) 'pasienhariini',
                     SUM(A.sutotaltransaksi) 'omzetbulanini',
                     COUNT(DISTINCT A.sukontak) 'pasienbulanini'
                   FROM fstoku A
                  WHERE A.susumber='IP' AND A.sustatus<>9
                    AND A.sucabang = '".$cabang."'
                    AND A.sutanggal >= DATE_FORMAT(CURDATE(),'%Y-%m-01')
                    AND A.sutanggal <= CURDATE()";

        header('Content-Type: application/json');
        echo $this->M_transaksi->get_data_query($query);
    }

}
