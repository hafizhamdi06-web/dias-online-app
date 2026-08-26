<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master_Satuan extends CI_Controller {

   function __construct() { 
		parent::__construct();
    if(!$this->session->has_userdata('nama')){
      redirect(base_url('exception'));
    }          
    $this->load->model('M_Master_Satuan');
    $this->load->model('M_transaksi');
   }

   function savedata(){
    if($_POST['id']==''){
      echo $this->M_Master_Satuan->tambahData();
    }else{
      echo $this->M_Master_Satuan->ubahData();      
    }
   }

   function deletedata(){
    echo $this->M_Master_Satuan->hapusData();          
   }

   function getdata(){
      if($_POST['id'] == '' || $_POST['id'] == null) {
        echo _pesanError("Data tidak ditemukan !");
        exit;
      }

      $query = "SELECT A.sid 'id', A.skode 'kode', A.snama 'nama', IFNULL(A.snilai,0) 'nilai', A.ssatuandasar 'sdasar'
                  FROM bsatuan A
                 WHERE A.sid='".$_POST['id']."'";
     
      header('Content-Type: application/json');
      echo $this->M_transaksi->get_data_query($query);
   }

}