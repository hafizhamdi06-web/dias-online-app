<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master_Bank extends CI_Controller {

    function __construct() { 
        parent::__construct();
        if(!$this->session->has_userdata('nama')){
          redirect(base_url('exception'));
        }          
        $this->load->model('M_Master_Bank');
        $this->load->model('M_transaksi');
    }

    function savedata(){
        if($this->input->post('id')==''){
          echo $this->M_Master_Bank->tambahData();
        }else{
          echo $this->M_Master_Bank->ubahData();      
        }
    }

    function deletedata(){
        echo $this->M_Master_Bank->hapusData();          
    }

    function getdata(){
        if($this->input->post('id') == '' || $this->input->post('id') == null) {
            echo _pesanError("Data tidak ditemukan !");
            exit;
        }

        $query = "SELECT A.bid 'id', A.bkode 'kode', A.bnama 'nama'
                    FROM bbank A
                   WHERE A.bid='".$this->input->post('id')."'";
       
        header('Content-Type: application/json');
        echo $this->M_transaksi->get_data_query($query);
    }


}