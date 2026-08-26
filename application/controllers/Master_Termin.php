<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master_Termin extends CI_Controller {

   function __construct() { 
		parent::__construct();
    if(!$this->session->has_userdata('nama')){
      redirect(base_url('exception'));
    }          
    $this->load->model('M_Master_Termin');
    $this->load->model('M_transaksi');
   }

   function savedata(){
    if($_POST['id']==''){
      echo $this->M_Master_Termin->tambahData();
    }else{
      echo $this->M_Master_Termin->ubahData();      
    }
   }

   function deletedata(){
    echo $this->M_Master_Termin->hapusData();          
   }

   function getdata(){
      if($_POST['id'] == '' || $_POST['id'] == null) {
        echo _pesanError("Data tidak ditemukan !");
        exit;
      }

      $query = "SELECT A.tid 'id', A.tkode 'kode', A.tnama 'nama', A.ttempo 'tempo'
                  FROM btermin A
                 WHERE A.tid='".$_POST['id']."'";
     
      header('Content-Type: application/json');
      echo $this->M_transaksi->get_data_query($query);
   }

}