<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Fina_Aktiva_Tetap extends CI_Controller {

   function __construct() { 
		parent::__construct();
    if(!$this->session->has_userdata('nama')){
      redirect(base_url('exception'));
    }          
		$this->load->model('M_transaksi');
   }

   function createsaldoawal(){
      $this->load->model('M_Fina_Aktiva_Saldo_Awal');
      echo $this->M_Fina_Aktiva_Saldo_Awal->create();
   }

   function createpembelian(){
      $this->load->model('M_Fina_Aktiva_Pembelian');    
      echo $this->M_Fina_Aktiva_Pembelian->create();
   }

   function createpenyusutan(){
      $this->load->model('M_Fina_Aktiva_Penyusutan');    
      echo $this->M_Fina_Aktiva_Penyusutan->create();
   }

}