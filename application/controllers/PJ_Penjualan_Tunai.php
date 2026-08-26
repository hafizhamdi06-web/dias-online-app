<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PJ_Penjualan_Tunai extends CI_Controller {

   function __construct() { 
		parent::__construct();
    if(!$this->session->has_userdata('nama')){
      redirect(base_url('exception'));
    }          
		$this->load->model('M_transaksi');
    $this->load->model('M_PJ_Penjualan_Tunai');    
   }

   function savedata(){
      if($_POST['id']==''){
        echo $this->M_PJ_Penjualan_Tunai->tambahTransaksi();
      }else{
        echo $this->M_PJ_Penjualan_Tunai->ubahTransaksi();      
      }
   }

   function deletedata(){
      echo $this->M_PJ_Penjualan_Tunai->hapusTransaksi();          
   }   

   function get_item() {
      $query  = "SELECT A.isatuan AS 'idsatuan', B.snama 'namasatuan', 
                        (SELECT kpenlevelharga FROM bkontak WHERE kid='".$_POST['kontak']."') 'level', 
                        A.ihargajual1 'hargajual',A.ihargajual2 'hargajual2',
                        A.ihargajual3 'hargajual3',A.ihargajual4 'hargajual4',      
                        (SELECT gid FROM bgudang WHERE gdefault=1 LIMIT 1) 'idgudang',
                        (SELECT gnama FROM bgudang WHERE gdefault=1 LIMIT 1) 'gudang'       
                   FROM bitem A LEFT JOIN bsatuan B ON A.isatuan=B.sid
                  WHERE A.iid='".$this->input->post('id')."'";
      header('Content-Type: application/json');
      echo $this->M_transaksi->get_data_query($query);
    }                   

   function get_item_kode() {
      $query  = "SELECT A.iid 'id',A.inama 'nama',A.isatuan AS 'idsatuan', B.snama 'namasatuan',
                        (SELECT kpenlevelharga FROM bkontak WHERE kid='".$_POST['kontak']."') 'level', 
                        A.ihargajual1 'hargajual',A.ihargajual2 'hargajual2',
                        A.ihargajual3 'hargajual3',A.ihargajual4 'hargajual4',       
                        (SELECT gid FROM bgudang WHERE gdefault=1 LIMIT 1) 'idgudang',
                        (SELECT gnama FROM bgudang WHERE gdefault=1 LIMIT 1) 'gudang'  
                   FROM bitem A LEFT JOIN bsatuan B ON A.isatuan=B.sid
                  WHERE A.ikode='".$this->input->post('id')."' OR A.ibarcode='".$this->input->post('id')."'";
      header('Content-Type: application/json');
      echo $this->M_transaksi->get_data_query($query);
    }  

   function getdata(){

      $transcode = $this->M_transaksi->prefixtrans(element('PJ_Penjualan_Tunai',NID));        
      $query = "SELECT A.ipuid 'id', A.ipunotransaksi 'nomor', DATE_FORMAT(A.iputanggal,'%d-%m-%Y') 'tanggal',
                       A.ipukontak 'kontakid', B.kkode 'kontakkode', B.knama 'kontak', 
                       A.ipujenispajak 'pajak', A.ipustatus 'status',
                       IFNULL(G.sutotalpajak,0) 'tpajak', 
                       IFNULL(G.sutotalbayar,0) 'tbayar', 
                       IFNULL(G.sutotaltransaksi,0) 'totaltrans', 
                       IFNULL(G.sutotalsisa,0) 'tsisa',
                       IFNULL(G.sutotalkas,0) 'bayarcash',
                       IFNULL(G.sutotalkartudebit,0) 'bayardebit',
                       IFNULL(G.sutotalkartukredit,0) 'bayarcredit',  
                       G.sunokartudebit 'bayardebitno',
                       G.sunokartukredit 'bayarcreditno',
                       G.subankdebit 'bayardebitbank',
                       G.subankkredit 'bayarcreditbank',
                       H.bkode 'bayardebitbankn',
                       I.bkode 'bayarcreditbankn',
                       C.ipditem 'iditem', D.ikode 'kditem', D.inama 'namaitem', 
                       C.ipdsatuan 'idsatuan', E.skode 'satuan', 
                       C.ipdgudang 'idgudang', F.gnama 'gudang',
                       IFNULL(C.ipdkeluar,0) 'qtydetil', 
                       IFNULL(C.ipdharga,0) 'hargadetil',
                       IFNULL(C.ipddiskon,0) 'diskon',
                       0 'persendiskon',
                       ((IFNULL(C.ipdharga,0)-IFNULL(C.ipddiskon,0))*IFNULL(C.ipdkeluar,0)) 'subtotaldetil' 
                    FROM einvoicepenjualanu A 
               LEFT JOIN bkontak B ON A.ipukontak=B.kid
               LEFT JOIN einvoicepenjualand C ON A.ipuid=C.ipdidipu 
               LEFT JOIN bitem D ON C.ipditem=D.iid 
               LEFT JOIN bsatuan E ON C.ipdsatuan=E.sid 
               LEFT JOIN bgudang F ON C.ipdgudang=F.gid
               LEFT JOIN fstoku G ON A.ipunobkg=G.suid  
               LEFT JOIN bbank H ON G.subankdebit=H.bid 
               LEFT JOIN bbank I ON G.subankkredit=I.bid 
                   WHERE A.ipusumber='".$transcode."'";

        if(!empty($_POST['id'])) {
            $query .= " AND A.ipuid='".$_POST['id']."'";
        }else{
            $query .= " AND A.ipunotransaksi='".$_POST['nomor']."'";
        }

        $query .= " ORDER BY C.ipdurutan ASC";
       
        header('Content-Type: application/json');
        echo $this->M_transaksi->get_data_query($query);
   }                     

}