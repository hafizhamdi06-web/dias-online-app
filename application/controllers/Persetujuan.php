<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Persetujuan extends CI_Controller {

   function __construct() {
        parent::__construct();
      if(!$this->session->has_userdata('nama')){
        redirect(base_url('exception'));
      }
      $this->load->model('M_transaksi');
      $this->load->model('M_Persetujuan');
   }

   function ajukan(){
      header('Content-Type: application/json');
      echo $this->M_Persetujuan->ajukan();
   }

   function cekstatus(){
      header('Content-Type: application/json');
      echo $this->M_Persetujuan->cekstatus();
   }

   function setuju(){
      echo $this->M_Persetujuan->setuju();
   }

   function tolak(){
      echo $this->M_Persetujuan->tolak();
   }

   function getverifikasidata(){
      header('Content-Type: application/json');
      echo $this->M_Persetujuan->getverifikasidata();
   }

   function listpending(){
      header('Content-Type: application/json');
      echo $this->M_Persetujuan->listpending();
   }

}
