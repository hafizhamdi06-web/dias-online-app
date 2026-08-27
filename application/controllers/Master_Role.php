<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master_Role extends CI_Controller {

   function __construct() {
        parent::__construct();
      if(!$this->session->has_userdata('nama')){
        redirect(base_url('exception'));
      }
        $this->load->model('M_Master_Role');
        $this->load->model('M_transaksi');
   }

   function savedata(){
    if($_POST['id']==''){
      echo $this->M_Master_Role->tambahData();
    }else{
      echo $this->M_Master_Role->ubahData();
    }
   }

   function deletedata(){
    echo $this->M_Master_Role->hapusData();
   }

   function getdata(){
      if($_POST['id'] == '' || $_POST['id'] == null) {
        echo _pesanError("Data tidak ditemukan !");
        exit;
      }

      $query = "SELECT A.ARID 'id', A.ARIDMENU 'idmenu', A.ARNAMAROLE 'nama'
                  FROM aarole A
                 WHERE A.ARID='".$_POST['id']."'";

      header('Content-Type: application/json');
      echo $this->M_transaksi->get_data_query($query);
   }

}
