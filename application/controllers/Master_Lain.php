<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master_Lain extends CI_Controller {

   function __construct() {
        parent::__construct();
      if(!$this->session->has_userdata('nama')){
        redirect(base_url('exception'));
      }
        $this->load->model('M_Master_Lain');
        $this->load->model('M_transaksi');
   }

   function savedata(){
    if($_POST['id']==''){
      echo $this->M_Master_Lain->tambahData();
    }else{
      echo $this->M_Master_Lain->ubahData();
    }
   }

   function deletedata(){
    echo $this->M_Master_Lain->hapusData();
   }

   function getdata(){
      if($_POST['id'] == '' || $_POST['id'] == null) {
        echo _pesanError("Data tidak ditemukan !");
        exit;
      }

      $query = "SELECT A.lid 'id', A.lkode 'kode', A.lnama 'nama', A.lketerangan 'keterangan',
                       A.ltipe 'tipe', A.lgudangid 'idgudang', B.gnama 'gudang'
                  FROM blain A LEFT JOIN bgudang B ON A.lgudangid=B.gid
                 WHERE A.lid='".$_POST['id']."'";

      header('Content-Type: application/json');
      echo $this->M_transaksi->get_data_query($query);
   }

}
