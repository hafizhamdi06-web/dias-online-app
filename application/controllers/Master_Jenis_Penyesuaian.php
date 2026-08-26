<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master_Jenis_Penyesuaian extends CI_Controller {

   function __construct() { 
		parent::__construct();
    if(!$this->session->has_userdata('nama')){
      redirect(base_url('exception'));
    }          
    $this->load->model('M_Master_Jenis_Penyesuaian');
    $this->load->model('M_transaksi');
   }

   function savedata(){
    if($_POST['id']==''){
      echo $this->M_Master_Jenis_Penyesuaian->tambahData();
    }else{
      echo $this->M_Master_Jenis_Penyesuaian->ubahData();      
    }
   }

   function deletedata(){
    echo $this->M_Master_Jenis_Penyesuaian->hapusData();          
   }

   function getdata(){
      if($_POST['id'] == '' || $_POST['id'] == null) {
        echo _pesanError("Data tidak ditemukan !");
        exit;
      }

      $query = "SELECT A.jid 'id', A.jkode 'kode', A.jnama 'nama', A.jakunbiaya 'idcoa',
                       B.cnocoa 'kodecoa', B.cnama 'coa'
                  FROM bjenispenyesuaian A
             LEFT JOIN bcoa B ON A.jakunbiaya=B.cid 
                 WHERE A.jid='".$_POST['id']."'";
     
      header('Content-Type: application/json');
      echo $this->M_transaksi->get_data_query($query);
   }

}