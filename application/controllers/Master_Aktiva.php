<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master_Aktiva extends CI_Controller {

    function __construct() { 
        parent::__construct();
        if(!$this->session->has_userdata('nama')) redirect(base_url('exception'));
        $this->load->model('M_Master_Aktiva');
        $this->load->model('M_transaksi');
    }

    function savedata(){
        if($this->input->post('id')==''){
          echo $this->M_Master_Aktiva->tambahData();
        }else{
          echo $this->M_Master_Aktiva->ubahData();      
        }
    }

    function deletedata(){
        echo $this->M_Master_Aktiva->hapusData();          
    }

    function getdata(){
        if($this->input->post('id') == '' || $this->input->post('id') == null) {
            echo _pesanError("Data tidak ditemukan !");
            exit;
        }

        $query = "SELECT A.aid 'id', A.akode 'kode', A.anama 'nama', A.akelompok 'idkelompok', B.aknama 'kelompok',
                         A.anomor 'serial', A.alokasi 'lokasi', A.adivisi 'iddivisi', C.dnama 'divisi',
                         DATE_FORMAT(A.atglbeli,'%d-%m-%Y') 'tglbeli', DATE_FORMAT(A.atglpakai,'%d-%m-%Y') 'tglpakai',
                         IFNULL(A.ajmlaktiva,0) 'qty', A.atipepenyusutan 'metode', IFNULL(A.ahargabeli,0) 'hargabeli',
                         ROUND(IFNULL(A.aakumbeban,0),2) 'akum', IFNULL(A.abebanperbulan,0) 'perbulan',
                         IFNULL(ROUND((A.aumur/12),0),0) 'utahun', IFNULL(ROUND(MOD(A.aumur,12),0),0) 'ubulan',
                         IFNULL(ROUND(IFNULL(A.ahargabeli,0),2)-ROUND(IFNULL(A.aakumbeban,0),2),0) 'nilaibuku',
                         IFNULL(A.anilairesidu,0) 'residu', A.aaktivatidakberwujud 'intangible', A.atgl15 'tgl15',
                         A.acoawriteoff 'idwo', D.cnama 'wo', A.acoaaktiva 'idcoaaktiva', E.cnama 'coaaktiva',
                         A.acoadepresiasi 'idcoapenyusutan', F.cnama 'coapenyusutan',
                         A.acoadepresiasiakum 'idcoaakum', G.cnama 'coaakum'
                    FROM baktiva A
               LEFT JOIN baktivakelompok B ON A.akelompok=B.akid  
               LEFT JOIN bdivisi C ON A.adivisi=C.did 
               LEFT JOIN bcoa D ON A.acoawriteoff=D.cid 
               LEFT JOIN bcoa E ON A.acoaaktiva=E.cid 
               LEFT JOIN bcoa F ON A.acoadepresiasi=F.cid              
               LEFT JOIN bcoa G ON A.acoadepresiasiakum=G.cid                           
                   WHERE A.aid='".$this->input->post('id')."'";
       
        header('Content-Type: application/json');
        echo $this->M_transaksi->get_data_query($query);
    }

}