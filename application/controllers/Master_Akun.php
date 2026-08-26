<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master_Akun extends CI_Controller {

    function __construct() { 
        parent::__construct();
        if(!$this->session->has_userdata('nama')) redirect(base_url('exception'));
        $this->load->model('M_Master_Akun');
        $this->load->model('M_transaksi');
    }

    function savedata(){
        if($this->input->post('id')==''){
            echo $this->M_Master_Akun->tambahData();
        }else{
            echo $this->M_Master_Akun->ubahData();      
        }
    }

    function deletedata(){
        echo $this->M_Master_Akun->hapusData();          
    }

    function getdata(){
        if($this->input->post('id') == '' || $this->input->post('id') == null) {
            echo _pesanError("Data tidak ditemukan !");
            exit;
        }

        $query = "SELECT A.cid 'id', A.cnama 'nama', A.ctipe 'idtipe', B.cgnama 'tipe', A.cnocoa 'nomor', A.cgd 'gd',
                         A.cparent 'idparent', C.cnama 'parent', A.csubdari 'sub', A.cuang 'iduang', D.ukode 'uang',
                         A.cdivisi 'iddivisi', E.dnama 'divisi', A.cbank 'idbank', F.bkode 'bank', A.cnoac 'nobank',
                         A.cactive 'status',G.csid 'said', G.cskontak 'saidkontak', DATE_FORMAT(G.cstanggal,'%d-%m-%Y') 'satanggal',
                         G.csjumlah 'sajumlah', H.knama 'sakontak'   
                    FROM bcoa A
               LEFT JOIN bcoagrup B ON A.ctipe=B.cgid
               LEFT JOIN bcoa C ON A.cparent=C.cid  
               LEFT JOIN buang D ON A.cuang=D.uid 
               LEFT JOIN bdivisi E ON A.cdivisi=E.did 
               LEFT JOIN bbank F ON A.cbank=F.bid
               LEFT JOIN bcoasa G ON A.cid=G.cscoa 
               LEFT JOIN bkontak H ON H.kid=G.cskontak 
                   WHERE A.cid='".$this->input->post('id')."'";
       
        header('Content-Type: application/json');
        echo $this->M_transaksi->get_data_query($query);
    }


}