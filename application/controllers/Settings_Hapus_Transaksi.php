<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings_Hapus_Transaksi extends CI_Controller {

   function __construct() { 
	parent::__construct();
    if(!$this->session->has_userdata('nama')){
      redirect(base_url('exception'));
    }          
	$this->load->model('M_Settings_Hapus_Transaksi');
   }

   function hapustransaksi(){
   		echo $this->M_Settings_Hapus_Transaksi->hapus();
   }

}