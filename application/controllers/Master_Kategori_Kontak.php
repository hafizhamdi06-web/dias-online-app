<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master_Kategori_Kontak extends CI_Controller {

   function __construct() { 
		parent::__construct();
    if(!$this->session->has_userdata('nama')){
      redirect(base_url('exception'));
    }          
    $this->load->model('M_Master_Kategori_Kontak');
    $this->load->model('M_transaksi');
   }

   function savedata(){
    if($_POST['id']==''){
      echo $this->M_Master_Kategori_Kontak->tambahData();
    }else{
      echo $this->M_Master_Kategori_Kontak->ubahData();      
    }
   }

   function deletedata(){
    echo $this->M_Master_Kategori_Kontak->hapusData();          
   }

   function getdata(){
      if($_POST['id'] == '' || $_POST['id'] == null) {
        echo _pesanError("Data tidak ditemukan !");
        exit;
      }

      $query = "SELECT A.ktid 'id', A.ktnama 'nama'
                  FROM bkontaktipe A
                 WHERE A.ktid='".$_POST['id']."'";
     
      header('Content-Type: application/json');
      echo $this->M_transaksi->get_data_query($query);
   }

}