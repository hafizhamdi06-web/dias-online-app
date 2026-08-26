<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PJ_Uang_Muka_Penjualan extends CI_Controller {

   function __construct() { 
  		parent::__construct();
      if(!$this->session->has_userdata('nama')){
        redirect(base_url('exception'));
      }          
  		$this->load->model('M_transaksi');
  	  $this->load->model('M_PJ_Uang_Muka_Penjualan');
   }

   function savedata(){
      if($_POST['id']==''){
        echo $this->M_PJ_Uang_Muka_Penjualan->tambahTransaksi();
      }else{
        echo $this->M_PJ_Uang_Muka_Penjualan->ubahTransaksi();      
      }
   }

   function deletedata(){
      echo $this->M_PJ_Uang_Muka_Penjualan->hapusTransaksi();          
   }

   function getdata(){

      $transcode = $this->M_transaksi->prefixtrans(element('PJ_Uang_Muka_Penjualan',NID));        
   		$query = "SELECT A.dpid 'id', A.dpnotransaksi 'nomor', DATE_FORMAT(A.dptanggal,'%d-%m-%Y') 'tanggal',
                       A.dpketerangan 'uraian', A.dpjumlah 'jumlah', A.dpkontak 'kontakid', B.kkode 'kontakkode',
                       B.knama 'kontak', A.dpcdp 'idcoadp', C.cnama 'coadp', A.dpckas 'idcoakas', D.cnama 'coakas',
                       A.dptipe 'tipe', A.dptermin 'idtermin', E.tkode 'termin'
                  FROM ddp A              
             LEFT JOIN bkontak B ON A.dpkontak=B.kid 
             LEFT JOIN bcoa C ON A.dpcdp=C.cid
             LEFT JOIN bcoa D ON A.dpckas=D.cid             
             LEFT JOIN btermin E ON A.dptermin=E.tid 
                 WHERE A.dpsumber='".$transcode."'";

        if(!empty($_POST['id'])) {
            $query .= " AND A.dpid='".$_POST['id']."'";
        }else{
            $query .= " AND A.dpnotransaksi='".$_POST['nomor']."'";
        }
       
        header('Content-Type: application/json');
        echo $this->M_transaksi->get_data_query($query);
   }

}