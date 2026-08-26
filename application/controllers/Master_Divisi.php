<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master_Divisi extends CI_Controller {

    function __construct() { 
        parent::__construct();
        if(!$this->session->has_userdata('nama')) redirect(base_url('exception'));
        $this->load->model('M_Master_Divisi');
        $this->load->model('M_transaksi');
    }

    function savedata(){
        if($this->input->post('id')==''){
          echo $this->M_Master_Divisi->tambahData();
        }else{
          echo $this->M_Master_Divisi->ubahData();      
        }
    }

    function deletedata(){
        echo $this->M_Master_Divisi->hapusData();          
    }

    function getdata(){
        if($this->input->post('id') == '' || $this->input->post('id') == null) {
            echo _pesanError("Data tidak ditemukan !");
            exit;
        }

        $query = "SELECT A.did 'id', A.dkode 'kode', A.dnama 'nama'
                    FROM bdivisi A
                   WHERE A.did='".$this->input->post('id')."'";
       
        header('Content-Type: application/json');
        echo $this->M_transaksi->get_data_query($query);
    }


}