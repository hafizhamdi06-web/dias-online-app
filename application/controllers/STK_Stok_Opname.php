<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class STK_Stok_Opname extends CI_Controller {

   function __construct() { 
  		parent::__construct();
      if(!$this->session->has_userdata('nama')){
        redirect(base_url('exception'));
      }          
  		$this->load->model('M_transaksi');
      $this->load->model('M_STK_Stok_Opname');        
   }

   function savedata(){
      if($_POST['id']==''){
        echo $this->M_STK_Stok_Opname->tambahTransaksi();
      }else{
        echo $this->M_STK_Stok_Opname->ubahTransaksi();      
      }
   }

   function deletedata(){
      echo $this->M_STK_Stok_Opname->hapusTransaksi();          
   }   

   function get_item() {
      $query  = "SELECT A.isatuan AS 'idsatuan', B.snama 'namasatuan', A.ihargabeli 'harga' 
                   FROM bitem A LEFT JOIN bsatuan B ON A.isatuan=B.sid
                  WHERE A.iid='".$_POST['id']."'";
      header('Content-Type: application/json');
      echo $this->M_transaksi->get_data_query($query);
    }                      

   function getdata(){
   		if(empty($_POST['id'])) {
   			echo _pesanError("Id transaksi tidak ditemukan !");
  			exit;
   		}

      $transcode = $this->M_transaksi->prefixtrans(element('STK_Stok_Opname',NID));        
      $query = "SELECT A.souid 'id', A.sounotransaksi 'nomor', DATE_FORMAT(A.soutanggal,'%d-%m-%Y') 'tanggal',
                       A.soukontak 'kontakid', B.kkode 'kontakkode', B.knama 'kontak', A.souuraian 'uraian',
                       A.sougudang 'idgudang', F.gnama 'gudang',
                       A.sounoref 'noref', A.soustatus 'status', C.soditem 'iditem', D.ikode 'kditem', 
                       D.inama 'namaitem', C.sodsatuan 'idsatuan', C.sodcatatan 'catdetil',
                       E.skode 'satuan', IFNULL(C.sodqty,0) 'qtydetil', 0 'subtotaldetil'                        
                    FROM fstokopnameu A 
               LEFT JOIN bkontak B ON A.soukontak=B.kid 
               LEFT JOIN fstokopnamed C ON A.souid=C.sodidsou
               LEFT JOIN bitem D ON C.soditem=D.iid 
               LEFT JOIN bsatuan E ON C.sodsatuan=E.sid 
               LEFT JOIN bgudang F ON A.sougudang=F.gid 
                   WHERE A.sousumber='".$transcode."' AND A.souid='".$_POST['id']."' ORDER BY C.sodurutan ASC ";

        header('Content-Type: application/json');
        echo $this->M_transaksi->get_data_query($query);
   }

}