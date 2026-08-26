<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_Report extends CI_Controller {

   function __construct() { 
		parent::__construct();
    if(!$this->session->has_userdata('nama')){
      redirect(base_url('exception'));
    }          
    $this->load->model('M_Admin_Report');
    $this->load->model('M_transaksi');
   }

   function savedata(){
    if($this->input->post('id')==''){
      echo $this->M_Admin_Report->tambahData();
    }else{
      echo $this->M_Admin_Report->ubahData();      
    }
   }

   function deletedata(){
    echo $this->M_Admin_Report->hapusData();          
   }

   function getdata(){
      if($this->input->post('id') == '' || $this->input->post('id') == null) {
        echo _pesanError("Data tidak ditemukan !");
        exit;
      }

      $query = "SELECT A.*
                  FROM aareport A 
                 WHERE A.arid='".$this->input->post('id')."'";
     
      header('Content-Type: application/json');
      echo $this->M_transaksi->get_data_query($query);
   }


}