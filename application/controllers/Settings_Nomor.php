<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings_Nomor extends CI_Controller {

   function __construct() { 
		parent::__construct();
    if(!$this->session->has_userdata('nama')){
      redirect(base_url('exception'));
    }          
    $this->load->model('M_Settings_Nomor');
    $this->load->model('M_transaksi');
   }

   function savedata(){
    if($_POST['id']==''){
      echo $this->M_Settings_Nomor->tambahData();
    }else{
      echo $this->M_Settings_Nomor->ubahData();      
    }
   }

   function deletedata(){
    echo $this->M_Settings_Nomor->hapusData();          
   }

   function getdata(){
      $query = "SELECT A.*,B.mnama 'menu' 
                  FROM anomor A LEFT JOIN amenu B ON A.nfmenu=B.mid 
                 WHERE A.nid = '".$_POST['id']."'";
     
      header('Content-Type: application/json');
      echo $this->M_transaksi->get_data_query($query);
   }

}