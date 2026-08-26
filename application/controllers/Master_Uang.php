<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master_Uang extends CI_Controller {

   function __construct() { 
		parent::__construct();
    if(!$this->session->has_userdata('nama')){
      redirect(base_url('exception'));
    }          
    $this->load->model('M_Master_Uang');
    $this->load->model('M_transaksi');
   }

   function savedata(){
    if($_POST['id']==''){
      echo $this->M_Master_Uang->tambahData();
    }else{
      echo $this->M_Master_Uang->ubahData();      
    }
   }

   function deletedata(){
    echo $this->M_Master_Uang->hapusData();          
   }

   function getdata(){
      if($_POST['id'] == '' || $_POST['id'] == null) {
        echo _pesanError("Data tidak ditemukan !");
        exit;
      }

      $query = "SELECT A.uid 'id', A.ukode 'kode', A.unama 'nama', A.usimbol 'simbol'
                  FROM buang A
                 WHERE A.uid='".$_POST['id']."'";
     
      header('Content-Type: application/json');
      echo $this->M_transaksi->get_data_query($query);
   }


}