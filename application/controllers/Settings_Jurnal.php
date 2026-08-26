<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings_Jurnal extends CI_Controller {

   function __construct() { 
		parent::__construct();
    if(!$this->session->has_userdata('nama')){
      redirect(base_url('exception'));
    }          
    $this->load->model('M_Settings_Jurnal');
    $this->load->model('M_transaksi');
   }

   function savedata(){
    if($_POST['id']==''){
      echo $this->M_Settings_Jurnal->tambahData();
    }else{
      echo $this->M_Settings_Jurnal->ubahData();      
    }
   }

   function deletedata(){
    echo $this->M_Settings_Jurnal->hapusData();          
   }

   function getdata(){
      $query = "SELECT A.ccid 'id', A.ccketerangan 'keterangan', A.cccoa 'idcoa', IFNULL(B.cnama,'[AUTO]') 'coa' 
                  FROM cconfigcoa A LEFT JOIN bcoa B ON A.cccoa=B.cid
                 WHERE A.cckode = '".$_POST['kode']."' AND A.ccketerangan='".$_POST['keterangan']."'";
     
      header('Content-Type: application/json');
      echo $this->M_transaksi->get_data_query($query);
   }

}