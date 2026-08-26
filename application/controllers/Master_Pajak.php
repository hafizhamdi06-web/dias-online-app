<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master_Pajak extends CI_Controller {

   function __construct() { 
		parent::__construct();
    if(!$this->session->has_userdata('nama')){
      redirect(base_url('exception'));
    }          
    $this->load->model('M_Master_Pajak');
    $this->load->model('M_transaksi');
   }

   function savedata(){
    if($_POST['id']==''){
      echo $this->M_Master_Pajak->tambahData();
    }else{
      echo $this->M_Master_Pajak->ubahData();      
    }
   }

   function deletedata(){
    echo $this->M_Master_Pajak->hapusData();          
   }

   function getdata(){
      if($_POST['id'] == '' || $_POST['id'] == null) {
        echo _pesanError("Data tidak ditemukan !");
        exit;
      }

      $query = "SELECT A.pid 'id', A.pkode 'kode', A.pnama 'nama', IFNULL(A.pnilai,0) 'nilai', A.ptipe 'tipe',
                       A.pcoain 'idcoain', A.pcoaout 'idcoaout',
                       B.cnama 'coain', C.cnama 'coaout'                           
                  FROM bpajak A
             LEFT JOIN bcoa B ON A.pcoain=B.cid 
             LEFT JOIN bcoa C ON A.pcoaout=C.cid  
                 WHERE A.pid='".$_POST['id']."'";
     
      header('Content-Type: application/json');
      echo $this->M_transaksi->get_data_query($query);
   }

}