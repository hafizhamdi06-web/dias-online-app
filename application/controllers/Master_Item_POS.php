<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Master_Item_POS extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        if (!$this->session->has_userdata('nama')) {
            redirect(base_url('exception'));
        }
        $this->load->model('M_Master_Item_POS');
        $this->load->model('M_transaksi');
    }

    function savedata(){
        if ($this->input->post('id') == '') {
            echo $this->M_Master_Item_POS->tambahData();
        } else {
            echo $this->M_Master_Item_POS->ubahData();
        }
    }

    function getdata(){
        if ($this->input->post('id') == '' || $this->input->post('id') == null) {
            echo _pesanError("Data tidak ditemukan !");
            exit;
        }

        $query = "SELECT A.iid 'id', A.ikode 'kode', A.inama 'nama', A.icomersialname 'namaweb',
                         A.ikategori 'kategori', A.istatus 'status', A.iserial 'serial',
                         IFNULL(A.iqtyperbox,1) 'qtyperbox',
                         A.isatuand 'idsatuand', C.skode 'satuand',
                         A.isatuan 'idsatuan', B.skode 'satuan',
                         IFNULL(A.istockmaksimal,0) 'stokmaks', IFNULL(A.istockminimal,0) 'stokmin',
                         IFNULL(A.istockreorder,0) 'stokreorder', IFNULL(A.imaxorder,3) 'maxorder'
                    FROM bitem A
               LEFT JOIN bsatuan B ON A.isatuan=B.sid
               LEFT JOIN bsatuan C ON A.isatuand=C.sid
                   WHERE A.iid='".$this->input->post('id')."'";

        header('Content-Type: application/json');
        echo $this->M_transaksi->get_data_query($query);
    }

    function deletedata(){
        echo $this->M_Master_Item_POS->hapusData();
    }

}
