<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master_Proyek extends CI_Controller {

   function __construct() { 
		parent::__construct();
    if(!$this->session->has_userdata('nama')){
      redirect(base_url('exception'));
    }          
    $this->load->model('M_Master_Proyek');
    $this->load->model('M_transaksi');
   }

   function savedata(){
    if($_POST['id']==''){
      echo $this->M_Master_Proyek->tambahData();
    }else{
      echo $this->M_Master_Proyek->ubahData();      
    }
   }

   function deletedata(){
    echo $this->M_Master_Proyek->hapusData();          
   }

   function getdata(){
      if($_POST['id'] == '' || $_POST['id'] == null) {
        echo _pesanError("Data tidak ditemukan !");
        exit;
      }

      $query = "SELECT A.pid 'id', A.pkode 'kode', A.pnama 'nama', IFNULL(A.pfee,0) 'nilai', A.ppelanggan 'pelanggan'
                  FROM bproyek A
                 WHERE A.pid='".$_POST['id']."'";
     
      header('Content-Type: application/json');
      echo $this->M_transaksi->get_data_query($query);
   }

}