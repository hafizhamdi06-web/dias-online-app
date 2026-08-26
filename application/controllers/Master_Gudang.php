<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master_Gudang extends CI_Controller {

    function __construct() { 
        parent::__construct();
        if(!$this->session->has_userdata('nama')) redirect(base_url('exception'));
        $this->load->model('M_Master_Gudang');
        $this->load->model('M_transaksi');
    }

    function savedata(){
        if($this->input->post('id')==''){
          echo $this->M_Master_Gudang->tambahData();
        }else{
          echo $this->M_Master_Gudang->ubahData();      
        }
    }

    function deletedata(){
        echo $this->M_Master_Gudang->hapusData();          
    }

    function getdata(){
        if($this->input->post('id') == '' || $this->input->post('id') == null) {
            echo _pesanError("Data tidak ditemukan !");
            exit;
        }

        $query = "SELECT A.gid 'id', A.gkode 'kode', A.gnama 'nama', A.gdefault 'def', A.gdivisi 'iddivisi',
                         B.dnama 'divisi', A.galamat1 'alamat', A.gkota 'kota', A.gpropinsi 'propinsi',
                         A.gnegara 'negara', A.gtelp 'telp', A.gfax 'faks', A.gkontak 'kontak'
                    FROM bgudang A
               LEFT JOIN bdivisi B ON A.gdivisi=B.did 
                   WHERE A.gid='".$this->input->post('id')."'";
       
        header('Content-Type: application/json');
        echo $this->M_transaksi->get_data_query($query);
    }

}