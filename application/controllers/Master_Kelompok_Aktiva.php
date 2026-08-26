<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master_Kelompok_Aktiva extends CI_Controller {

   function __construct() { 
		parent::__construct();
    if(!$this->session->has_userdata('nama')){
      redirect(base_url('exception'));
    }          
    $this->load->model('M_Master_Kelompok_Aktiva');
    $this->load->model('M_transaksi');
   }

   function savedata(){
    if($_POST['id']==''){
      echo $this->M_Master_Kelompok_Aktiva->tambahData();
    }else{
      echo $this->M_Master_Kelompok_Aktiva->ubahData();      
    }
   }

   function deletedata(){
    echo $this->M_Master_Kelompok_Aktiva->hapusData();          
   }

   function getdata(){
      if($_POST['id'] == '' || $_POST['id'] == null) {
        echo _pesanError("Data tidak ditemukan !");
        exit;
      }

      $query = "SELECT A.akid 'id', A.akkode 'kode', A.aknama 'nama', A.akumur 'umur',
                       A.akcoaaktiva 'idcoaaktiva', B.cnama 'coaaktiva',
                       A.akcoadepresiasi 'idcoadepresiasi', C.cnama 'coadepresiasi',
                       A.akcoadepresiasiakum 'idcoadepresiasiakum', D.cnama 'coadepresiasiakum',
                       A.akcoawriteoff 'idcoawriteoff', E.cnama 'coawriteoff'
                  FROM baktivakelompok A
             LEFT JOIN bcoa B ON A.akcoaaktiva=B.cid 
             LEFT JOIN bcoa C ON A.akcoadepresiasi=C.cid 
             LEFT JOIN bcoa D ON A.akcoadepresiasiakum=D.cid 
             LEFT JOIN bcoa E ON A.akcoawriteoff=E.cid 
                 WHERE A.akid='".$_POST['id']."'";
     
      header('Content-Type: application/json');
      echo $this->M_transaksi->get_data_query($query);
   }

}